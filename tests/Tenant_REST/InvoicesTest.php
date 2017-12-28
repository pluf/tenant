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
require_once 'Pluf.php';

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class Tenant_REST_InvoicesTest extends TestCase
{

    /**
     * @beforeClass
     */
    public static function installApps()
    {
        Pluf::start(__DIR__ . '/../conf/config.php');
        $m = new Pluf_Migration(array(
            'Pluf',
            'User',
            'Role',
            'Group',
            'Tenant'
        ));
        $m->install();
        
        
        // Test tenant
        $tenant = new Pluf_Tenant();
        $tenant->domain = 'localhost';
        $tenant->subdomain = 'www';
        $tenant->validate = true;
        if (true !== $tenant->create()) {
            throw new Pluf_Exception('Faile to create new tenant');
        }
        
        $m->init($tenant);
        
        // Test user
        $user = new User();
        $user->login = 'test';
        $user->first_name = 'test';
        $user->last_name = 'test';
        $user->email = 'toto@example.com';
        $user->setPassword('test');
        $user->active = true;
        
        if(!isset($GLOBALS['_PX_request'])){
            $GLOBALS['_PX_request'] = new Pluf_HTTP_Request('/');
        }
        $GLOBALS['_PX_request']->tenant= $tenant;
        if (true !== $user->create()) {
            throw new Exception();
        }
        
        $per = Role::getFromString('Pluf.owner');
        $user->setAssoc($per);
    }

    /**
     * @afterClass
     */
    public static function uninstallApps()
    {
        $m = new Pluf_Migration(array(
            'Pluf',
            'User',
            'Role',
            'Group',
            'Tenant'
        ));
        $m->unInstall();
    }

    /**
     * Getting invoice list
     *
     * @test
     */
    public function shouldSupportMultipleLogin()
    {
        $urls =array(
            array(
                'app' => 'Tenant',
                'regex' => '#^/api/tenant#',
                'base' => '',
                'sub' => include 'Tenant/urls.php'
            ),
            array(
                'app' => 'User',
                'regex' => '#^/api/user#',
                'base' => '',
                'sub' => include 'User/urls.php'
            )
        );
        
        // login
        for($i = 0; $i < 10; $i++){
            $client = new Test_Client($urls);
            $response = $client->post('/api/user/login', array(
                'login' => 'test',
                'password' => 'test'
            ));
            Test_Assert::assertResponseStatusCode($response, 200, 'Fail to login');
            // Current user is valid
            $response = $client->get('/api/user');
            Test_Assert::assertResponseStatusCode($response, 200, 'Fail to login');
            Test_Assert::assertResponseNotAnonymousModel($response, 'Current user is anonymous');
        }
    }
    
    /**
     * Getting invoice list
     *
     * @test
     */
    public function testFindInvoices()
    {
        $client = new Test_Client(array(
            array(
                'app' => 'Tenant',
                'regex' => '#^/api/tenant#',
                'base' => '',
                'sub' => include 'Tenant/urls.php'
            ),
            array(
                'app' => 'User',
                'regex' => '#^/api/user#',
                'base' => '',
                'sub' => include 'User/urls.php'
            )
        ));

        // login
        $response = $client->post('/api/user/login', array(
            'login' => 'test',
            'password' => 'test'
        ));
        Test_Assert::assertResponseStatusCode($response, 200, 'Fail to login');
        
        // Current user is valid
        $response = $client->get('/api/user');
        Test_Assert::assertResponseStatusCode($response, 200, 'Fail to login');
        Test_Assert::assertResponseNotAnonymousModel($response, 'Current user is anonymous');
        
        // find
        $response = $client->get('/api/tenant/invoice/find');
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
        Test_Assert::assertResponsePaginateList($response, 'Find result is not JSON paginated list');
    }

    /**
     * Getting invoice list
     *
     * Check non-empty list
     *
     * @test
     */
    public function testFindInvoicesNonEmpty()
    {
        $client = new Test_Client(array(
            array(
                'app' => 'Tenant',
                'regex' => '#^/api/tenant#',
                'base' => '',
                'sub' => include 'Tenant/urls.php'
            ),
            array(
                'app' => 'User',
                'regex' => '#^/api/user#',
                'base' => '',
                'sub' => include 'User/urls.php'
            )
        ));
        // login
        $response = $client->post('/api/user/login', array(
            'login' => 'test',
            'password' => 'test'
        ));
        Test_Assert::assertResponseStatusCode($response, 200, 'Fail to login');
        
        // Current user is valid
        $response = $client->get('/api/user');
        Test_Assert::assertResponseStatusCode($response, 200, 'Fail to login');
        Test_Assert::assertResponseNotAnonymousModel($response, 'Current user is anonymous');
        
        $i = new Tenant_Invoice();
        $i->title = 'test';
        $i->descscription = 'test';
        $i->amount = 1000;
        $i->due_dtime = gmdate('Y-m-d H:i:s');
        $i->create();
        
        // find
        $response = $client->get('/api/tenant/invoice/find');
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
        Test_Assert::assertResponsePaginateList($response, 'Find result is not JSON paginated list');
        Test_Assert::assertResponseNonEmptyPaginateList($response, 'No object is in list');
        
        // delete
        $i->delete();
    }
    
    
    /**
     * Getting invoice
     *
     * @test
     */
    public function testGetInvoice()
    {
        $client = new Test_Client(array(
            array(
                'app' => 'Tenant',
                'regex' => '#^/api/tenant#',
                'base' => '',
                'sub' => include 'Tenant/urls.php'
            ),
            array(
                'app' => 'User',
                'regex' => '#^/api/user#',
                'base' => '',
                'sub' => include 'User/urls.php'
            )
        ));
        // login
        $response = $client->post('/api/user/login', array(
            'login' => 'test',
            'password' => 'test'
        ));
        Test_Assert::assertResponseStatusCode($response, 200, 'Fail to login');
        
        $i = new Tenant_Invoice();
        $i->title = 'test';
        $i->descscription = 'test';
        $i->amount = 1000;
        $i->due_dtime = gmdate('Y-m-d H:i:s');
        $i->create();
        
        // find
        $response = $client->get('/api/tenant/invoice/'. $i->id);
        Test_Assert::assertResponseNotNull($response);
        Test_Assert::assertResponseStatusCode($response, 200);
        Test_Assert::assertResponseNotAnonymousModel($response, 'Invoice not foudn');
        
        // delete
        $i->delete();
    }
}

