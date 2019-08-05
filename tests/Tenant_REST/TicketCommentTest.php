<?php
// /*
//  * This file is part of Pluf Framework, a simple PHP Application Framework.
//  * Copyright (C) 2010-2020 Phoinex Scholars Co. (http://dpq.co.ir)
//  *
//  * This program is free software: you can redistribute it and/or modify
//  * it under the terms of the GNU General Public License as published by
//  * the Free Software Foundation, either version 3 of the License, or
//  * (at your option) any later version.
//  *
//  * This program is distributed in the hope that it will be useful,
//  * but WITHOUT ANY WARRANTY; without even the implied warranty of
//  * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
//  * GNU General Public License for more details.
//  *
//  * You should have received a copy of the GNU General Public License
//  * along with this program. If not, see <http://www.gnu.org/licenses/>.
//  */
// require_once 'Pluf.php';

// set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../Base/');

// /**
//  * @backupGlobals disabled
//  * @backupStaticAttributes disabled
//  */
// class Tenant_REST_TicketCommentsTest extends AbstractBasicTest
// {
//     private static function getApi(){
//         $myAPI = array(
//             array(
//                 'app' => 'Tenant',
//                 'regex' => '#^/api/tenant#',
//                 'base' => '',
//                 'sub' => include 'Tenant/urls.php'
//             ),
//             array(
//                 'app' => 'User',
//                 'regex' => '#^/api/user#',
//                 'base' => '',
//                 'sub' => include 'User/urls.php'
//             )
//         );
//         return $myAPI;
//     }

//     private static function getApiV2(){
//         $myAPI = array(
//             array(
//                 'app' => 'Tenant',
//                 'regex' => '#^/api/v2/tenant#',
//                 'base' => '',
//                 'sub' => include 'Tenant/urls-v2.php'
//             ),
//             array(
//                 'app' => 'User',
//                 'regex' => '#^/api/v2/user#',
//                 'base' => '',
//                 'sub' => include 'User/urls-v2.php'
//             )
//         );
//         return $myAPI;
//     }

//     /**
//      * Getting tenant tickets
//      *
//      * Call tenant to get list of tickets
//      *
//      * @test
//      */
//     public function testFindTikcetComments()
//     {
//         $client = new Test_Client(self::getApiV2());
//         // login
//         $response = $client->post('/api/v2/user/login', array(
//             'login' => 'test',
//             'password' => 'test'
//         ));
//         Test_Assert::assertResponseStatusCode($response, 200, 'Fail to login');

//         // Create ticket
//         $user = new User_Account();
//         $user = $user->getUser('test');

//         $t = new Tenant_Ticket();
//         $t->subject = 'test';
//         $t->description = 'test';
//         $t->type = 'bug';
//         $t->status = 'new';
//         $t->requester_id = $user;
//         $t->create();

//         // find comments
//         $response = $client->get('/api/v2/tenant/tickets/' . $t->id . '/comments');
//         Test_Assert::assertResponseNotNull($response, 'Find result is empty');
//         Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
//         Test_Assert::assertResponsePaginateList($response, 'Find result is not JSON paginated list');

//         // delete
//         $t->delete();
//     }

//     /**
//      * Getting not empety comments
//      *
//      * @test
//      */
//     public function testFindTikcetCommentSNotEmpty()
//     {
//         $client = new Test_Client(self::getApiV2());
//         // login
//         $response = $client->post('/api/v2/user/login', array(
//             'login' => 'test',
//             'password' => 'test'
//         ));
//         Test_Assert::assertResponseStatusCode($response, 200, 'Fail to login');

//         // Create ticket
//         $user = new User_Account();
//         $user = $user->getUser('test');

//         $t = new Tenant_Ticket();
//         $t->subject = 'test';
//         $t->description = 'test';
//         $t->type = 'bug';
//         $t->status = 'new';
//         $t->requester_id = $user;
//         $t->create();

//         $c = new Tenant_Comment();
//         $c->title = 'test';
//         $c->description = 'test';
//         $c->author_id = $user;
//         $c->ticket_id = $t;
//         $c->create();

//         // find comments
//         $response = $client->get('/api/v2/tenant/tickets/' . $t->id . '/comments');
//         Test_Assert::assertResponseNotNull($response, 'Find result is empty');
//         Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
//         Test_Assert::assertResponsePaginateList($response);
//         Test_Assert::assertResponseNonEmptyPaginateList($response);

//         // delete
//         $c->delete();
//         $t->delete();
//     }

//     /**
//      * Getting comment
//      *
//      * @test
//      */
//     public function testGetTikcetComment()
//     {
//         $client = new Test_Client(self::getApiV2());
//         // login
//         $response = $client->post('/api/v2/user/login', array(
//             'login' => 'test',
//             'password' => 'test'
//         ));
//         Test_Assert::assertResponseStatusCode($response, 200, 'Fail to login');

//         // Create ticket
//         $user = new User_Account();
//         $user = $user->getUser('test');

//         $t = new Tenant_Ticket();
//         $t->subject = 'test';
//         $t->description = 'test';
//         $t->type = 'bug';
//         $t->status = 'new';
//         $t->requester_id = $user;
//         $t->create();

//         $c = new Tenant_Comment();
//         $c->title = 'test';
//         $c->description = 'test';
//         $c->author_id = $user;
//         $c->ticket_id = $t;
//         $c->create();

//         // find comments
//         $response = $client->get('/api/v2/tenant/tickets/' . $t->id . '/comments/' . $c->id);
//         Test_Assert::assertResponseNotNull($response, 'Find result is empty');
//         Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
//         Test_Assert::assertResponseAsModel($response);
//         Test_Assert::assertResponseNotAnonymousModel($response);

//         // delete
//         $c->delete();
//         $t->delete();
//     }

//     /**
//      * Creating comment
//      *
//      * @test
//      */
//     public function testCreateTikcetComment()
//     {
//         $client = new Test_Client(self::getApiV2());
//         // login
//         $response = $client->post('/api/v2/user/login', array(
//             'login' => 'test',
//             'password' => 'test'
//         ));
//         Test_Assert::assertResponseStatusCode($response, 200, 'Fail to login');

//         // Create ticket
//         $user = new User_Account();
//         $user = $user->getUser('test');

//         $t = new Tenant_Ticket();
//         $t->subject = 'test';
//         $t->description = 'test';
//         $t->type = 'bug';
//         $t->status = 'new';
//         $t->requester_id = $user;
//         $t->create();

//         // find comments
//         $response = $client->post('/api/v2/tenant/tickets/' . $t->id . '/comments', array(
//             'title' => 'test',
//             'description' => 'test'
//         ));
//         Test_Assert::assertResponseStatusCode($response, 200);
//         $tc = json_decode($response->content, true);

//         // find comments
//         $response = $client->get('/api/v2/tenant/tickets/' . $t->id . '/comments');
//         Test_Assert::assertResponseNotNull($response, 'Find result is empty');
//         Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
//         Test_Assert::assertResponsePaginateList($response);
//         Test_Assert::assertResponseNonEmptyPaginateList($response);

//         // delete
//         $c = new Tenant_Comment($tc['id']);
//         $c->delete();
//         $t->delete();
//     }

//     /**
//      * Creating comment
//      *
//      * @test
//      */
//     public function testUpdateTikcetComment()
//     {
//         $client = new Test_Client(self::getApiV2());
//         // login
//         $response = $client->post('/api/v2/user/login', array(
//             'login' => 'test',
//             'password' => 'test'
//         ));
//         Test_Assert::assertResponseStatusCode($response, 200, 'Fail to login');

//         // Create ticket
//         $user = new User_Account();
//         $user = $user->getUser('test');

//         $t = new Tenant_Ticket();
//         $t->subject = 'test';
//         $t->description = 'test';
//         $t->type = 'bug';
//         $t->status = 'new';
//         $t->requester_id = $user;
//         $t->create();

//         // find comments
//         $response = $client->post('/api/v2/tenant/tickets/' . $t->id . '/comments', array(
//             'title' => 'test',
//             'description' => 'test'
//         ));
//         Test_Assert::assertResponseStatusCode($response, 200);
//         $tc = json_decode($response->content, true);

//         // update
//         $response = $client->post('/api/v2/tenant/tickets/' . $t->id . '/comments/' . $tc['id'], array(
//             'title' => 'test new title',
//             'description' => 'test'
//         ));
//         Test_Assert::assertResponseStatusCode($response, 200);

//         // find comments
//         $response = $client->get('/api/v2/tenant/tickets/' . $t->id . '/comments');
//         Test_Assert::assertResponseNotNull($response, 'Find result is empty');
//         Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
//         Test_Assert::assertResponsePaginateList($response);
//         Test_Assert::assertResponseNonEmptyPaginateList($response);

//         // delete
//         $c = new Tenant_Comment($tc['id']);
//         $c->delete();
//         $t->delete();
//     }

//     /**
//      * Creating comment
//      *
//      * @test
//      */
//     public function testDeleteTikcetComment()
//     {
//         $client = new Test_Client(self::getApiV2());
//         // login
//         $response = $client->post('/api/v2/user/login', array(
//             'login' => 'test',
//             'password' => 'test'
//         ));
//         Test_Assert::assertResponseStatusCode($response, 200, 'Fail to login');

//         // Create ticket
//         $user = new User_Account();
//         $user = $user->getUser('test');

//         $t = new Tenant_Ticket();
//         $t->subject = 'test';
//         $t->description = 'test';
//         $t->type = 'bug';
//         $t->status = 'new';
//         $t->requester_id = $user;
//         $t->create();

//         // find comments
//         $response = $client->post('/api/v2/tenant/tickets/' . $t->id . '/comments', array(
//             'title' => 'test',
//             'description' => 'test'
//         ));
//         Test_Assert::assertResponseStatusCode($response, 200);
//         $tc = json_decode($response->content, true);

//         // update
//         $response = $client->delete('/api/v2/tenant/tickets/' . $t->id . '/comments/' . $tc['id']);
//         Test_Assert::assertResponseStatusCode($response, 200);

//         // find comments
//         $response = $client->get('/api/v2/tenant/tickets/' . $t->id . '/comments');
//         Test_Assert::assertResponseNotNull($response, 'Find result is empty');
//         Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
//         Test_Assert::assertResponsePaginateList($response);
//         Test_Assert::assertResponseEmptyPaginateList($response);

//         // delete
//         $t->delete();
//     }
// }

