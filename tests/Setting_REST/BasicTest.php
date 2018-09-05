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
class Setting_REST_BasicTest extends AbstractBasicTest
{

    private static $client = null;

    private static $ownerClient = null;

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
        // Owner client
        self::$ownerClient = new Test_Client(array(
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
        $response = self::$ownerClient->post('/api/v2/user/login', array(
            'login' => 'test',
            'password' => 'test'
        ));
        Test_Assert::assertNotNull($response);
        Test_Assert::assertEquals($response->status_code, 200);
    }

    /**
     * Getting list of properties
     *
     * @test
     */
    public function anonymousCanGetListOfSettings()
    {
        $response = self::$client->get('/api/v2/tenant/settings');
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
        Test_Assert::assertResponsePaginateList($response, 'Find result is not JSON paginated list');
    }

    /**
     * Getting list of properties with owner
     *
     * @test
     */
    public function ownerCanGetListOfSettings()
    {
        // Getting list
        $response = self::$ownerClient->get('/api/v2/tenant/settings');
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
        Test_Assert::assertResponsePaginateList($response, 'Find result is not JSON paginated list');
    }

    /**
     * Create a new setting in system
     *
     * @test
     */
    public function ownerCanCreateASetting()
    {
        // Getting list
        $values = array(
            'key' => 'KEY-TEST-' . rand(),
            'value' => 'NOT SET',
            'mode' => Tenant_Setting::MOD_PUBLIC
        );
        $response = self::$ownerClient->post('/api/v2/tenant/settings', $values);
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');

        $setting = new Tenant_Setting();
        $list = $setting->getList();
        Test_Assert::assertTrue(sizeof($list) > 0, 'Setting is not created');
        Test_Assert::assertEquals($values['value'], Tenant_Service::setting($values['key']), 'Values are not equal.');
    }

    /**
     * Create and update a new setting in system by owner
     *
     * @test
     */
    public function ownerCanCreateAndGetSettingByKey()
    {
        // Getting list
        $values = array(
            'key' => 'KEY-TEST-' . rand(),
            'value' => 'NOT SET',
            'mode' => Tenant_Setting::MOD_PUBLIC
        );
        $response = self::$ownerClient->post('/api/v2/tenant/settings', $values);
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');

        $setting = new Tenant_Setting();
        $list = $setting->getList();
        Test_Assert::assertTrue(sizeof($list) > 0, 'Setting is not created');
        Test_Assert::assertEquals($values['value'], Tenant_Service::setting($values['key']), 'Values are not equal.');

        $response = self::$ownerClient->get('/api/v2/tenant/settings/' . $values['key']);
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
    }

    /**
     * Create and update a new setting in system by owner
     *
     * @test
     */
    public function ownerCanCreateAndGetSettingById()
    {
        // Getting list
        $values = array(
            'key' => 'KEY-TEST-' . rand(),
            'value' => 'NOT SET',
            'mode' => Tenant_Setting::MOD_PUBLIC
        );
        $response = self::$ownerClient->post('/api/v2/tenant/settings', $values);
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');

        $setting = new Tenant_Setting();
        $list = $setting->getList();
        Test_Assert::assertTrue(sizeof($list) > 0, 'Setting is not created');
        Test_Assert::assertEquals($values['value'], Tenant_Service::setting($values['key']), 'Values are not equal.');

        $sql = new Pluf_SQL('`key`=%s', array(
            $values['key']
        ));
        $one = $setting->getOne(array(
            'filter' => $sql->gen()
        ));
        Test_Assert::assertNotNull($one, 'Setting not found with key');

        $response = self::$ownerClient->get('/api/v2/tenant/settings/' . $one->id);
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
    }

    /**
     * Create and update a new setting in system by owner
     *
     * @test
     */
    public function ownerCanCreateAndDeleteSettingById()
    {
        // Getting list
        $values = array(
            'key' => 'KEY-TEST-' . rand(),
            'value' => 'NOT SET',
            'mode' => Tenant_Setting::MOD_PUBLIC
        );
        $response = self::$ownerClient->post('/api/v2/tenant/settings', $values);
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');

        // Get setting form db
        $setting = new Tenant_Setting();
        $sql = new Pluf_SQL('`key`=%s', array(
            $values['key']
        ));
        $one = $setting->getOne(array(
            'filter' => $sql->gen()
        ));
        Test_Assert::assertNotNull($one, 'Setting not found with key');

        // delete by id
        $response = self::$ownerClient->delete('/api/v2/tenant/settings/' . $one->id);
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');

        // Check if deleted
        $one = $setting->getOne(array(
            'filter' => $sql->gen()
        ));
        Test_Assert::assertNull($one, 'Setting is not deleted');
    }

    /**
     * Create and update a new setting in system by owner
     *
     * @test
     */
    public function ownerCanCreateAndUpdateSettingById()
    {
        // Getting list
        $values = array(
            'key' => 'KEY-TEST-' . rand(),
            'value' => 'NOT SET',
            'mode' => Tenant_Setting::MOD_PUBLIC
        );
        $response = self::$ownerClient->post('/api/v2/tenant/settings', $values);
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');

        $setting = new Tenant_Setting();
        $sql = new Pluf_SQL('`key`=%s', array(
            $values['key']
        ));
        $one = $setting->getOne(array(
            'filter' => $sql->gen()
        ));
        Test_Assert::assertNotNull($one, 'Setting not found with key');

        $values['value'] = 'new value' . rand();
        $response = self::$ownerClient->post('/api/v2/tenant/settings/' . $one->id, $values);
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');

        $one = $setting->getOne(array(
            'filter' => $sql->gen()
        ));
        Test_Assert::assertNotNull($one, 'Setting not found with key');
        Test_Assert::assertEquals($values['value'], $one->value);
    }
}