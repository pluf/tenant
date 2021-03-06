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

use Pluf\Test\Client;
use Pluf\Test\Base\AbstractBasicTestMt;
use Tenant_Comment;
use Tenant_Ticket;
use User_Account;

/**
 *
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class TicketCommentsTest extends AbstractBasicTestMt
{

    private static function getApi()
    {
        $myAPI = array(
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
        return $myAPI;
    }

    private static function getApiV2()
    {
        $myAPI = array(
            array(
                'app' => 'Tenant',
                'regex' => '#^/tenant#',
                'base' => '',
                'sub' => include 'Tenant/urls-v2.php'
            ),
            array(
                'app' => 'User',
                'regex' => '#^/user#',
                'base' => '',
                'sub' => include 'User/urls-v2.php'
            )
        );
        return $myAPI;
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
        $client = new Client();
        // login
        $response = $client->post('/user/login', array(
            'login' => 'test',
            'password' => 'test'
        ));
        $this->assertResponseStatusCode($response, 200, 'Fail to login');

        // Create ticket
        $user = new User_Account();
        $user = $user->getUser('test');

        $t = new Tenant_Ticket();
        $t->subject = 'test';
        $t->description = 'test';
        $t->type = 'bug';
        $t->status = 'new';
        $t->requester_id = $user;
        $t->create();

        // find comments
        $response = $client->get('/tenant/tickets/' . $t->id . '/comments');
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
        $this->assertResponsePaginateList($response, 'Find result is not JSON paginated list');

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
        $client = new Client();
        // login
        $response = $client->post('/user/login', array(
            'login' => 'test',
            'password' => 'test'
        ));
        $this->assertResponseStatusCode($response, 200, 'Fail to login');

        // Create ticket
        $user = new User_Account();
        $user = $user->getUser('test');

        $t = new Tenant_Ticket();
        $t->subject = 'test';
        $t->description = 'test';
        $t->type = 'bug';
        $t->status = 'new';
        $t->requester_id = $user;
        $t->create();

        $c = new Tenant_Comment();
        $c->title = 'test';
        $c->description = 'test';
        $c->author_id = $user;
        $c->ticket_id = $t;
        $c->create();

        // find comments
        $response = $client->get('/tenant/tickets/' . $t->id . '/comments');
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
        $this->assertResponsePaginateList($response);
        $this->assertResponseNonEmptyPaginateList($response);

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
        $client = new Client();
        // login
        $response = $client->post('/user/login', array(
            'login' => 'test',
            'password' => 'test'
        ));
        $this->assertResponseStatusCode($response, 200, 'Fail to login');

        // Create ticket
        $user = new User_Account();
        $user = $user->getUser('test');

        $t = new Tenant_Ticket();
        $t->subject = 'test';
        $t->description = 'test';
        $t->type = 'bug';
        $t->status = 'new';
        $t->requester_id = $user;
        $t->create();

        $c = new Tenant_Comment();
        $c->title = 'test';
        $c->description = 'test';
        $c->author_id = $user;
        $c->ticket_id = $t;
        $c->create();

        // find comments
        $response = $client->get('/tenant/tickets/' . $t->id . '/comments/' . $c->id);
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
        $this->assertResponseAsModel($response);
        $this->assertResponseNotAnonymousModel($response);

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
        $client = new Client();
        // login
        $response = $client->post('/user/login', array(
            'login' => 'test',
            'password' => 'test'
        ));
        $this->assertResponseStatusCode($response, 200, 'Fail to login');

        // Create ticket
        $user = new User_Account();
        $user = $user->getUser('test');

        $t = new Tenant_Ticket();
        $t->subject = 'test';
        $t->description = 'test';
        $t->type = 'bug';
        $t->status = 'new';
        $t->requester_id = $user;
        $t->create();

        // find comments
        $response = $client->post('/tenant/tickets/' . $t->id . '/comments', array(
            'title' => 'test',
            'description' => 'test'
        ));
        $this->assertResponseStatusCode($response, 200);
        $tc = json_decode($response->content, true);

        // find comments
        $response = $client->get('/tenant/tickets/' . $t->id . '/comments');
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
        $this->assertResponsePaginateList($response);
        $this->assertResponseNonEmptyPaginateList($response);

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
        $client = new Client();
        // login
        $response = $client->post('/user/login', array(
            'login' => 'test',
            'password' => 'test'
        ));
        $this->assertResponseStatusCode($response, 200, 'Fail to login');

        // Create ticket
        $user = new User_Account();
        $user = $user->getUser('test');

        $t = new Tenant_Ticket();
        $t->subject = 'test';
        $t->description = 'test';
        $t->type = 'bug';
        $t->status = 'new';
        $t->requester_id = $user;
        $t->create();

        // find comments
        $response = $client->post('/tenant/tickets/' . $t->id . '/comments', array(
            'title' => 'test',
            'description' => 'test'
        ));
        $this->assertResponseStatusCode($response, 200);
        $tc = json_decode($response->content, true);

        // update
        $response = $client->post('/tenant/tickets/' . $t->id . '/comments/' . $tc['id'], array(
            'title' => 'test new title',
            'description' => 'test'
        ));
        $this->assertResponseStatusCode($response, 200);

        // find comments
        $response = $client->get('/tenant/tickets/' . $t->id . '/comments');
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
        $this->assertResponsePaginateList($response);
        $this->assertResponseNonEmptyPaginateList($response);

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
        $client = new Client();
        // login
        $response = $client->post('/user/login', array(
            'login' => 'test',
            'password' => 'test'
        ));
        $this->assertResponseStatusCode($response, 200, 'Fail to login');

        // Create ticket
        $user = new User_Account();
        $user = $user->getUser('test');

        $t = new Tenant_Ticket();
        $t->subject = 'test';
        $t->description = 'test';
        $t->type = 'bug';
        $t->status = 'new';
        $t->requester_id = $user;
        $t->create();

        // find comments
        $response = $client->post('/tenant/tickets/' . $t->id . '/comments', array(
            'title' => 'test',
            'description' => 'test'
        ));
        $this->assertResponseStatusCode($response, 200);
        $tc = json_decode($response->content, true);

        // update
        $response = $client->delete('/tenant/tickets/' . $t->id . '/comments/' . $tc['id']);
        $this->assertResponseStatusCode($response, 200);

        // find comments
        $response = $client->get('/tenant/tickets/' . $t->id . '/comments');
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
        $this->assertResponsePaginateList($response);
        $this->assertResponseEmptyPaginateList($response);

        // delete
        $t->delete();
    }
}

