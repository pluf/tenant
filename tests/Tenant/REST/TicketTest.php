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

class TicketsTest extends AbstractBasicTestMt
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
    public function testFindTikcets()
    {
        $client = new Client();
        // login
        $response = $client->post('/user/login', array(
            'login' => 'test',
            'password' => 'test'
        ));
        $this->assertResponseStatusCode($response, 200, 'Fail to login');

        // find teckets
        $response = $client->get('/tenant/tickets');
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
        $this->assertResponsePaginateList($response, 'Find result is not JSON paginated list');
    }

    /**
     * Create a ticket
     *
     * @test
     */
    public function testFindTikcetsNotEmpty()
    {
        $client = new Client();
        // login
        $response = $client->post('/user/login', array(
            'login' => 'test',
            'password' => 'test'
        ));
        $this->assertResponseStatusCode($response, 200, 'Fail to login');

        // create tecket
        $response = $client->post('/tenant/tickets', array(
            'type' => 'bug',
            'subject' => 'test ticket',
            'description' => 'it is not possible to test'
        ));
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
        $this->assertResponseNotAnonymousModel($response, 'Ticket is not created');
        $t = json_decode($response->content, true);

        // find teckets
        $response = $client->get('/tenant/tickets');
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
        $this->assertResponsePaginateList($response, 'Find result is not JSON paginated list');
        $this->assertResponseNonEmptyPaginateList($response, 'No ticket is created');

        // delete ticket
        $response = $client->delete('/tenant/tickets/' . $t['id']);
        $this->assertResponseStatusCode($response, 200, 'Ticket is removed');
    }

    /**
     * Create a ticket
     *
     * @test
     */
    public function testCreateTikcet()
    {
        $client = new Client();
        // login
        $response = $client->post('/user/login', array(
            'login' => 'test',
            'password' => 'test'
        ));
        $this->assertResponseStatusCode($response, 200, 'Fail to login');

        // create tecket
        $response = $client->post('/tenant/tickets', array(
            'type' => 'bug',
            'subject' => 'test ticket',
            'description' => 'it is not possible to test'
        ));
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
        $this->assertResponseNotAnonymousModel($response, 'Ticket is not created');
        $t = json_decode($response->content, true);

        // delete ticket
        $response = $client->delete('/tenant/tickets/' . $t['id']);
        $this->assertResponseStatusCode($response, 200, 'Ticket is removed');
    }

    /**
     * Get a ticket
     *
     * @test
     */
    public function testGetTikcet()
    {
        $client = new Client();
        // login
        $response = $client->post('/user/login', array(
            'login' => 'test',
            'password' => 'test'
        ));
        $this->assertResponseStatusCode($response, 200, 'Fail to login');

        // create tecket
        $response = $client->post('/tenant/tickets', array(
            'type' => 'bug',
            'subject' => 'test ticket',
            'description' => 'it is not possible to test'
        ));
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
        $this->assertResponseNotAnonymousModel($response, 'Ticket is not created');

        // Get tecket
        $t = json_decode($response->content, true);
        $response = $client->get('/tenant/tickets/' . $t['id']);
        $this->assertResponseNotAnonymousModel($response, 'Ticket is not find');

        // delete ticket
        $response = $client->delete('/tenant/tickets/' . $t['id']);
        $this->assertResponseStatusCode($response, 200, 'Ticket is removed');
    }
}

