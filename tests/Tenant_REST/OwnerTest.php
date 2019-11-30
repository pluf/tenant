<?php
/*
 * This file is part of Pluf Framework, a simple PHP Application Framework.
 * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\IncompleteTestError;
require_once 'Pluf.php';


/**
 *
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class Tenant_REST_OwnerTest extends TestCase
{

    var $ownerClient;
    var $memberClient;
    var $anonymousClient;
    var $member;
    var $subtenantInfo;

    /**
     *
     * @beforeClass
     */
    public static function installApps()
    {
        $cfg = include __DIR__ . '/../conf/config.php';
        $cfg['multitenant'] = true;
        Pluf::start($cfg);
        $m = new Pluf_Migration(Pluf::f('installed_apps'));
        $m->install();

        // Create default tenant
        $dftTnt = new Tenant_Tenant();
        $dftTnt->subdomain = 'www';
        $dftTnt->domain = 'www.domain.ir';
        if(true !== $dftTnt->create()){
            throw new Pluf_Exception();
        }
        
        $m->init($dftTnt);

        // Test users:
        // Owner
        $user = new User_Account();
        $user->login = 'test_owner';
        $user->is_active = true;
        if (true !== $user->create()) {
            throw new Exception();
        }
        // credential of owner
        $credit = new User_Credential();
        $credit->setFromFormData(array(
            'account_id' => $user->id
        ));
        $credit->setPassword('test');
        if (true !== $credit->create()) {
            throw new Exception();
        }
        // add role to owner
        $per = User_Role::getFromString('tenant.owner');
        $user->setAssoc($per);
        
        // Member
        $user = new User_Account();
        $user->login = 'test_member';
        $user->is_active = true;
        if (true !== $user->create()) {
            throw new Exception();
        }
        // credential of member
        $credit = new User_Credential();
        $credit->setFromFormData(array(
            'account_id' => $user->id
        ));
        $credit->setPassword('test');
        if (true !== $credit->create()) {
            throw new Exception();
        }
        // add role to member
        $per = User_Role::getFromString('tenant.member');
        $user->setAssoc($per);

        
    }

    /**
     *
     * @afterClass
     */
    public static function uninstallApps()
    {
        $m = new Pluf_Migration(Pluf::f('installed_apps'));
        $m->unInstall();
    }

    /**
     * 
     * @before
     */
    public function init(){
        // Anonymouse client
        $this->anonymousClient = new Test_Client(array(
            array(
                'app' => 'Tenant',
                'regex' => '#^/api/v2/tenant#',
                'base' => '',
                'sub' => include 'Tenant/urls-v2.php'
            ),
            array(
                'app' => 'User',
                'regex' => '#^/api/v2/user#',
                'base' => '',
                'sub' => include 'User/urls-v2.php'
            )
        ));
        // Member client
        $this->memberClient = new Test_Client(array(
            array(
                'app' => 'Tenant',
                'regex' => '#^/api/v2/tenant#',
                'base' => '',
                'sub' => include 'Tenant/urls-v2.php'
            ),
            array(
                'app' => 'User',
                'regex' => '#^/api/v2/user#',
                'base' => '',
                'sub' => include 'User/urls-v2.php'
            )
        ));
        // Login
        $response = $this->memberClient->post('/api/v2/user/login', array(
            'login' => 'test_member',
            'password' => 'test'
        ));
        Test_Assert::assertNotNull($response);
        Test_Assert::assertEquals($response->status_code, 200);
        $this->member = Tenant_Owner::getOwner('test_member');
        
        // Owner client
        $this->ownerClient = new Test_Client(array(
            array(
                'app' => 'Tenant',
                'regex' => '#^/api/v2/tenant#',
                'base' => '',
                'sub' => include 'Tenant/urls-v2.php'
            ),
            array(
                'app' => 'User',
                'regex' => '#^/api/v2/user#',
                'base' => '',
                'sub' => include 'User/urls-v2.php'
            )
        ));
        // Login
        $response = $this->ownerClient->post('/api/v2/user/login', array(
            'login' => 'test_owner',
            'password' => 'test'
        ));
        Test_Assert::assertNotNull($response);
        Test_Assert::assertEquals($response->status_code, 200);
        
        // Create a subtenant
        $subdomain = 'test' . rand();
        $data = array(
            'subdomain' => $subdomain,
            'domain' => $subdomain . '.domain.ir'
        );
        $response = $this->ownerClient->post('/api/v2/tenant/tenants', $data);
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
        Test_Assert::assertResponseAsModel($response, 200, 'Fail to create tenant');
        $this->subtenantInfo = json_decode($response->content, true);
    }
    
    /**
     * Getting list of owners of a subtenant
     *
     * @test
     */
    public function gettingListOfOwners()
    {
        // Getting list
        $response = $this->memberClient->get('/api/v2/tenant/tenants/' . $this->subtenantInfo['id'] . '/owners');
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
        Test_Assert::assertResponsePaginateList($response, 'Find result is not JSON paginated list');
    }

    /**
     * Add an owner to a subtenant
     *
     * @test
     */
    public function addingRemovingOwner()
    {
        // Add owner
        $data = $this->member->jsonSerialize();
        $response = $this->memberClient->post('/api/v2/tenant/tenants/' . $this->subtenantInfo['id'] . '/owners', $data);
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');

        $subtenant = new Tenant_Tenant($this->subtenantInfo['id']);
        $list = $subtenant->get_owners_list();
        Test_Assert::assertTrue(sizeof($list) > 0, 'Member is not added to the list of owners of the subtenant');
        
        $list = $this->member->get_tenants_list();
        Test_Assert::assertTrue(sizeof($list) > 0, 'Subtenant is not added to list of owned the tenants by the member');

        // Remove owner
        $response = $this->memberClient->delete('/api/v2/tenant/tenants/' . $this->subtenantInfo['id'] . '/owners/' . $this->member->id);
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');

        $list = $subtenant->get_owners_list();
        Test_Assert::assertTrue(sizeof($list) == 0, 'Member is not removed from the list of owners of the subtenant');
        
        $list = $this->member->get_tenants_list();
        Test_Assert::assertTrue(sizeof($list) == 0, 'Subtenant is not removed from the list of owned tenants by the member');
        
    }

    public function anonymousGettingListOfOwners(){
        $this->expectException(Pluf_Exception_Unauthorized::class);
        $response = $this->anonymousClient->get('/api/v2/tenant/tenants/' . $this->subtenantInfo['id'] . '/owners');
        Test_Assert::assertResponseStatusCode($response, 401, 'Anonymous user should not be allowed to see the list of owners of a subtenant');
    }
    
    public function anonymousRemovingOwner(){
        $this->expectException(Pluf_Exception_Unauthorized::class);
        $response = $this->anonymousClient->post('/api/v2/tenant/tenants/' . $this->subtenantInfo['id'] . '/owners', $this->member->jsonSerialize());
        Test_Assert::assertResponseStatusCode($response, 401, 'Anonymous user should not be allowed to add somebody to the list of owners of a subtenant');
    }
    
    public function anonymousAddingOwner(){
        $this->expectException(Pluf_Exception_Unauthorized::class);
        $response = $this->anonymousClient->delete('/api/v2/tenant/tenants/' . $this->subtenantInfo['id'] . '/owners/' . $this->member->id);
        Test_Assert::assertResponseStatusCode($response, 401, 'Anonymous user should not be allowed to remove somebody from the list of owners of a subtenant');
    }
    
}