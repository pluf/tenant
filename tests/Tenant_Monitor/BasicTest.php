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
use PHPUnit\Framework\IncompleteTestError;
require_once 'Pluf.php';

set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../Base/');

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class User_Monitor_BasicsTest extends AbstractBasicTest
{

    /**
     * @test
     */
    public function currentUserRest()
    {
        
        $client = new Test_Client(array(
            array(
                'app' => 'User',
                'regex' => '#^/api/v2/user#',
                'base' => '',
                'sub' => include 'User/urls-v2.php'
            ),
            array(
                'app' => 'Monitor',
                'regex' => '#^/api/v2/monitor#',
                'base' => '',
                'sub' => include 'Monitor/urls-v2.php'
            )
        ));
        
        // Change detail
        $user = new User_Account();
        $user = $user->getUser('test');
        
        // Login
        $response = $client->post('/api/v2/user/login', array(
            'login' => 'test',
            'password' => 'test'
        ));
        $this->assertNotNull($response);
        $this->assertEquals($response->status_code, 200);
        
        // Monitor owner
        $response = $client->get('/api/v2/monitor/tags/user/metrics/owner');
        $this->assertNotNull($response);
        $this->assertEquals($response->status_code, 200);
    }

}