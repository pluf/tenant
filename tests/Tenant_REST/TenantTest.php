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
require_once 'Pluf.php';

set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../Base/');

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class Tenant_REST_TenantTest extends AbstractBasicTest
{
    
    /**
     * Getting tenant info
     *
     * Call tenant to get current tenant information.
     *
     * @test
     */
    public function testDefaultTenant()
    {
        // XXX: Hadi, 1397-06-14: Now there is no API to get information of current tenant.
//         $client = new Test_Client(array(
//             array(
//                 'app' => 'Tenant',
//                 'regex' => '#^/api/v2/tenant#',
//                 'base' => '',
//                 'sub' => include 'Tenant/urls-v2.php'
//             )
//         ));
//         $response = $client->get('/api/v2/tenant/current');
//         $this->assertNotNull($response);
//         $this->assertEquals($response->status_code, 200);
    }
}

