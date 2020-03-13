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
namespace Pluf\Test\Tenant\Monitor;

use Pluf\Test\Client;
use Pluf\Test\Base\AbstractBasicTest;
use User_Account;

class User_Monitor_BasicsTest extends AbstractBasicTest
{

    /**
     *
     * @test
     */
    public function getOwnerMonitor()
    {
        $client = new Client();

        // Change detail
        $user = new User_Account();
        $user = $user->getUser('test');

        // Login
        $response = $client->post('/user/login', array(
            'login' => 'test',
            'password' => 'test'
        ));
        $this->assertNotNull($response);
        $this->assertEquals($response->status_code, 200);

        // Monitor owner
        $response = $client->get('/monitor/tags/user/metrics/owner');
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
        $this->assertResponseAsModel($response, 'Is not a valid model');
    }
}

