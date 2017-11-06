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
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class Tenant_REST_TenantTest extends TestCase
{

    /**
     * @before
     */
    protected function setUp()
    {
        Pluf::start(dirname(__FILE__) . '/../conf/config-01.php');
        $db = Pluf::db();
        $schema = Pluf::factory('Pluf_DB_Schema', $db);
        $models = array(
            // Core module
            'Pluf_Group',
            'Pluf_User',
            'Pluf_Permission',
            'Pluf_RowPermission',
            'Pluf_Session',
            'Pluf_Message',
            'Pluf_Tenant',
            
            // Tenant module
            'Tenant_Invoice',
            'Tenant_Comment',
            'Tenant_Ticket'
        );
        foreach ($models as $model) {
            $schema->model = Pluf::factory($model);
            $schema->dropTables();
            if (true !== ($res = $schema->createTables())) {
                throw new Exception($res);
            }
        }
        
        // Test user
        $user = new Pluf_User();
        $user->login = 'test';
        $user->first_name = 'test';
        $user->last_name = 'test';
        $user->email = 'toto@example.com';
        $user->setPassword('test');
        $user->active = true;
        $user->administrator = true;
        if (true !== $user->create()) {
            throw new Exception();
        }
        
        // Test tenant
        $tenant = new Pluf_Tenant();
        $tenant->domain = 'localhost';
        $tenant->subdomain = 'test';
        $tenant->validate = true;
        if (true !== $tenant->create()) {
            throw new Pluf_Exception('Faile to create new tenant');
        }
    }

    /**
     * Getting tenant info
     *
     * Call tenant to get current tenant information.
     *
     * @test
     */
    public function testDefaultTenant()
    {
        $client = new Test_Client(array(
            array(
                'app' => 'Tenant',
                'regex' => '#^/api/tenant#',
                'base' => '',
                'sub' => include 'Tenant/urls.php'
            )
        ));
        $response = $client->get('/api/tenant/current');
        $this->assertNotNull($response);
        $this->assertEquals($response->status_code, 200);
    }

}

