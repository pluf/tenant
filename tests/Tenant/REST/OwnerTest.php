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
namespace Pluf\Test\Tenant\Rest;

use Pluf\Test\TestCase;
use Pluf\Exception;
use Pluf\Test\Client;
use Pluf;
use Pluf_Exception_Unauthorized;
use Pluf_Migration;
use Pluf_Tenant;
use Tenant_Owner;
use Tenant_Service;
use Tenant_Tenant;
use User_Account;
use User_Credential;
use User_Role;

class OwnerTest extends TestCase
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
        $cfg = include __DIR__ . '/../../conf/config.php';
        $cfg['multitenant'] = true;
        Pluf::start($cfg);
        $m = new Pluf_Migration();
        $m->install();

        $dftTnt = Tenant_Service::createNewTenant(array(
            'subdomain' => 'www',
            'domain' => 'www.domain.ir'
        ));
        Pluf_Tenant::setCurrent($dftTnt);
        
        
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
        $m = new Pluf_Migration();
        $m->unInstall();
    }

    /**
     *
     * @before
     */
    public function init()
    {
        // Anonymouse client
        $this->anonymousClient = new Client();
        // Member client
        $this->memberClient = new Client();
        // Login
        $response = $this->memberClient->post('/user/login', array(
            'login' => 'test_member',
            'password' => 'test'
        ));
        $this->assertNotNull($response);
        $this->assertEquals($response->status_code, 200);
        $this->member = Tenant_Owner::getOwner('test_member');

        // Owner client
        $this->ownerClient = new Client();
        // Login
        $response = $this->ownerClient->post('/user/login', array(
            'login' => 'test_owner',
            'password' => 'test'
        ));
        $this->assertNotNull($response);
        $this->assertEquals($response->status_code, 200);

        // Create a subtenant
        $subdomain = 'test' . rand();
        $data = array(
            'subdomain' => $subdomain,
            'domain' => $subdomain . '.domain.ir'
        );
        $response = $this->ownerClient->post('/tenant/tenants', $data);
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
        $this->assertResponseAsModel($response, 200, 'Fail to create tenant');
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
        $response = $this->memberClient->get('/tenant/tenants/' . $this->subtenantInfo['id'] . '/owners');
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
        $this->assertResponsePaginateList($response, 'Find result is not JSON paginated list');
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
        $response = $this->memberClient->post('/tenant/tenants/' . $this->subtenantInfo['id'] . '/owners', $data);
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');

        $subtenant = new Tenant_Tenant($this->subtenantInfo['id']);
        $list = $subtenant->get_owners_list();
        $this->assertTrue(sizeof($list) > 0, 'Member is not added to the list of owners of the subtenant');

        $list = $this->member->get_tenants_list();
        $this->assertTrue(sizeof($list) > 0, 'Subtenant is not added to list of owned the tenants by the member');

        // Remove owner
        $response = $this->memberClient->delete('/tenant/tenants/' . $this->subtenantInfo['id'] . '/owners/' . $this->member->id);
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');

        $list = $subtenant->get_owners_list();
        $this->assertTrue(sizeof($list) == 0, 'Member is not removed from the list of owners of the subtenant');

        $list = $this->member->get_tenants_list();
        $this->assertTrue(sizeof($list) == 0, 'Subtenant is not removed from the list of owned tenants by the member');
    }

    public function anonymousGettingListOfOwners()
    {
        $this->expectException(Pluf_Exception_Unauthorized::class);
        $response = $this->anonymousClient->get('/tenant/tenants/' . $this->subtenantInfo['id'] . '/owners');
        $this->assertResponseStatusCode($response, 401, 'Anonymous user should not be allowed to see the list of owners of a subtenant');
    }

    public function anonymousRemovingOwner()
    {
        $this->expectException(Pluf_Exception_Unauthorized::class);
        $response = $this->anonymousClient->post('/tenant/tenants/' . $this->subtenantInfo['id'] . '/owners', $this->member->jsonSerialize());
        $this->assertResponseStatusCode($response, 401, 'Anonymous user should not be allowed to add somebody to the list of owners of a subtenant');
    }

    public function anonymousAddingOwner()
    {
        $this->expectException(Pluf_Exception_Unauthorized::class);
        $response = $this->anonymousClient->delete('/tenant/tenants/' . $this->subtenantInfo['id'] . '/owners/' . $this->member->id);
        $this->assertResponseStatusCode($response, 401, 'Anonymous user should not be allowed to remove somebody from the list of owners of a subtenant');
    }
}