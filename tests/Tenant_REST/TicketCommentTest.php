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
class Tenant_REST_TicketCommentsTest extends TestCase
{
    /**
     * @beforeClass
     */
    public static function installApps()
    {
        Pluf::start(__DIR__ . '/../conf/config.php');
        $m = new Pluf_Migration(Pluf::f('installed_apps'));
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
        $m = new Pluf_Migration(Pluf::f('installed_apps'));
        $m->unInstall();
    }

    /**
     * Getting tenant tickets
     *
     * Call tenant to get list of tickets
     *
     * @test
     */
    public function testFindTikcetComments()
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
        
        // Create ticket
        $user = new User();
        $user = $user->getUser('test');
        
        $t = new Tenant_Ticket();
        $t->subject = 'test';
        $t->description = 'test';
        $t->type = 'bug';
        $t->status = 'new';
        $t->requester = $user;
        $t->create();
        
        // find comments
        $response = $client->get('/api/tenant/ticket/' . $t->id . '/comment/find');
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
        Test_Assert::assertResponsePaginateList($response, 'Find result is not JSON paginated list');
        
        // delete
        $t->delete();
    }

    /**
     * Getting not empety comments
     *
     * @test
     */
    public function testFindTikcetCommentSNotEmpty()
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
        
        // Create ticket
        $user = new User();
        $user = $user->getUser('test');
        
        $t = new Tenant_Ticket();
        $t->subject = 'test';
        $t->description = 'test';
        $t->type = 'bug';
        $t->status = 'new';
        $t->requester = $user;
        $t->create();
        
        $c = new Tenant_Comment();
        $c->title = 'test';
        $c->description = 'test';
        $c->author = $user;
        $c->ticket = $t;
        $c->create();
        
        // find comments
        $response = $client->get('/api/tenant/ticket/' . $t->id . '/comment/find');
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
        Test_Assert::assertResponsePaginateList($response);
        Test_Assert::assertResponseNonEmptyPaginateList($response);
        
        // delete
        $c->delete();
        $t->delete();
    }

    /**
     * Getting comment
     *
     * @test
     */
    public function testGetTikcetComment()
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
        
        // Create ticket
        $user = new User();
        $user = $user->getUser('test');
        
        $t = new Tenant_Ticket();
        $t->subject = 'test';
        $t->description = 'test';
        $t->type = 'bug';
        $t->status = 'new';
        $t->requester = $user;
        $t->create();
        
        $c = new Tenant_Comment();
        $c->title = 'test';
        $c->description = 'test';
        $c->author = $user;
        $c->ticket = $t;
        $c->create();
        
        // find comments
        $response = $client->get('/api/tenant/ticket/' . $t->id . '/comment/' . $c->id);
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
        Test_Assert::assertResponseAsModel($response);
        Test_Assert::assertResponseNotAnonymousModel($response);
        
        // delete
        $c->delete();
        $t->delete();
    }

    /**
     * Creating comment
     *
     * @test
     */
    public function testCreateTikcetComment()
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
        
        // Create ticket
        $user = new User();
        $user = $user->getUser('test');
        
        $t = new Tenant_Ticket();
        $t->subject = 'test';
        $t->description = 'test';
        $t->type = 'bug';
        $t->status = 'new';
        $t->requester = $user;
        $t->create();
        
        // find comments
        $response = $client->post('/api/tenant/ticket/' . $t->id . '/comment/new', array(
            'title' => 'test',
            'description' => 'test'
        ));
        Test_Assert::assertResponseStatusCode($response, 200);
        $tc = json_decode($response->content, true);
        
        // find comments
        $response = $client->get('/api/tenant/ticket/' . $t->id . '/comment/find');
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
        Test_Assert::assertResponsePaginateList($response);
        Test_Assert::assertResponseNonEmptyPaginateList($response);
        
        // delete
        $c = new Tenant_Comment($tc['id']);
        $c->delete();
        $t->delete();
    }

    /**
     * Creating comment
     *
     * @test
     */
    public function testUpdateTikcetComment()
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
        
        // Create ticket
        $user = new User();
        $user = $user->getUser('test');
        
        $t = new Tenant_Ticket();
        $t->subject = 'test';
        $t->description = 'test';
        $t->type = 'bug';
        $t->status = 'new';
        $t->requester = $user;
        $t->create();
        
        // find comments
        $response = $client->post('/api/tenant/ticket/' . $t->id . '/comment/new', array(
            'title' => 'test',
            'description' => 'test'
        ));
        Test_Assert::assertResponseStatusCode($response, 200);
        $tc = json_decode($response->content, true);
        
        // update
        $response = $client->post('/api/tenant/ticket/' . $t->id . '/comment/' . $tc['id'], array(
            'title' => 'test new title',
            'description' => 'test'
        ));
        Test_Assert::assertResponseStatusCode($response, 200);
        
        // find comments
        $response = $client->get('/api/tenant/ticket/' . $t->id . '/comment/find');
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
        Test_Assert::assertResponsePaginateList($response);
        Test_Assert::assertResponseNonEmptyPaginateList($response);
        
        // delete
        $c = new Tenant_Comment($tc['id']);
        $c->delete();
        $t->delete();
    }

    /**
     * Creating comment
     *
     * @test
     */
    public function testDeleteTikcetComment()
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
        
        // Create ticket
        $user = new User();
        $user = $user->getUser('test');
        
        $t = new Tenant_Ticket();
        $t->subject = 'test';
        $t->description = 'test';
        $t->type = 'bug';
        $t->status = 'new';
        $t->requester = $user;
        $t->create();
        
        // find comments
        $response = $client->post('/api/tenant/ticket/' . $t->id . '/comment/new', array(
            'title' => 'test',
            'description' => 'test'
        ));
        Test_Assert::assertResponseStatusCode($response, 200);
        $tc = json_decode($response->content, true);
        
        // update
        $response = $client->delete('/api/tenant/ticket/' . $t->id . '/comment/' . $tc['id']);
        Test_Assert::assertResponseStatusCode($response, 200);
        
        // find comments
        $response = $client->get('/api/tenant/ticket/' . $t->id . '/comment/find');
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
        Test_Assert::assertResponsePaginateList($response);
        Test_Assert::assertResponseEmptyPaginateList($response);
        
        // delete
        $t->delete();
    }
}

