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
 *
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class Setting_REST_BasicOwnerTest extends AbstractBasicTest
{

    private static $client = null;

    /**
     *
     * @beforeClass
     */
    public static function installApps()
    {
        parent::installApps();
        // Anonymouse client
        self::$client = new Test_Client(array(
            array(
                'app' => 'Tenant',
                'regex' => '#^/api/v2/tenant#',
                'base' => '',
                'sub' => include 'Tenant/urls-v2.php'
            ),
            array(
                'app' => 'User',
                'regex' => '#^/api/v2/user#',
                'base' => '',
                'sub' => include 'User/urls-v2.php'
            )
        ));
        // Login
        self::$client->clean(true);
        $response = self::$client->post('/api/v2/user/login', array(
            'login' => 'test',
            'password' => 'test'
        ));
        $this->assertNotNull($response);
        $this->assertEquals($response->status_code, 200);
    }

    /**
     * Getting list of properties with owner
     *
     * @test
     */
    public function ownerCanGetListOfSettings()
    {
        // Getting list
        $response = self::$client->get('/api/v2/tenant/resources');
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
        $this->assertResponsePaginateList($response, 'Find result is not JSON paginated list');
    }

    /**
     * Create a new setting in system
     *
     * @test
     */
    public function ownerCanCreateAResource()
    {
        // Getting list
        $values = array(
            'path' => '/KEy/.TEST/' . rand(),
            'title' => 'NOT SET',
            'description' => 'This is a test resources'
        );
        $response = self::$client->post('/api/v2/tenant/resources', $values);
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');

        $setting = new Tenant_Resource();
        $list = $setting->getList();
        $this->assertTrue(sizeof($list) > 0, 'Setting is not created');

        foreach ($list as $resource) {
            $resource->delete();
        }
    }

    /**
     * Create a new resource in system by owner
     *
     * @test
     */
    public function ownerCanCreateAndGetSettingByKey()
    {
        // Getting list
        $values = array(
            'path' => '/KEy/.TEST/' . rand(),
            'title' => 'NOT SET',
            'description' => 'This is a test resources'
        );
        $response = self::$client->post('/api/v2/tenant/resources', $values);
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');

        $setting = new Tenant_Resource();
        $list = $setting->getList();
        $this->assertTrue(sizeof($list) > 0, 'Setting is not created');

        foreach ($list as $resource) {
            $response = self::$client->get('/api/v2/tenant/resources/' . $resource->id);
            $this->assertResponseNotNull($response, 'Find result is empty');
            $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
        }

        // delete
        foreach ($list as $resource) {
            $resource->delete();
        }
    }

    /**
     * Create and update a new setting in system by owner
     *
     * @test
     */
    public function ownerCanCreateAndGetSettingById()
    {
        $values = array(
            'path' => '/KEy/.TEST/' . rand(),
            'title' => 'NOT SET',
            'description' => 'This is a test resources'
        );
        $response = self::$client->post('/api/v2/tenant/resources', $values);
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');

        $setting = new Tenant_Resource();
        $list = $setting->getList();
        $this->assertTrue(sizeof($list) > 0, 'Setting is not created');

        $sql = new Pluf_SQL('`path`=%s', array(
            $values['path']
        ));
        $one = $setting->getOne(array(
            'filter' => $sql->gen()
        ));
        $this->assertNotNull($one, 'Resource not found with key');

        $response = self::$client->get('/api/v2/tenant/resources/' . $one->id);
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
    }

    /**
     * Create and update a new setting in system by owner
     *
     * @test
     */
    public function ownerCanCreateAndDeleteSettingById()
    {
        $values = array(
            'path' => '/KEy/.TEST/' . rand(),
            'title' => 'NOT SET',
            'description' => 'This is a test resources'
        );
        $response = self::$client->post('/api/v2/tenant/resources', $values);
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');

        // Get setting form db
        $setting = new Tenant_Resource();
        $sql = new Pluf_SQL('`path`=%s', array(
            $values['path']
        ));
        $one = $setting->getOne(array(
            'filter' => $sql->gen()
        ));
        $this->assertNotNull($one, 'Resource not found with path');

        // delete by id
        $response = self::$client->delete('/api/v2/tenant/resources/' . $one->id);
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');

        // Check if deleted
        $one = $setting->getOne(array(
            'filter' => $sql->gen()
        ));
        $this->assertNull($one, 'Resource is not deleted');
    }
}