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
namespace Pluf\Test\Setting;

use Pluf\Test\Client;
use Pluf\Test\Base\AbstractBasicTest;
use Pluf_SQL;
use Tenant_Service;
use Tenant_Setting;

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
        self::$client = new Client();
        // Owner client
        self::$ownerClient = new Client();
        // Login
        $response = self::$ownerClient->post('/user/login', array(
            'login' => 'test',
            'password' => 'test'
        ));
        self::assertNotNull($response);
        self::assertEquals($response->status_code, 200);
    }

    /**
     * Getting list of properties
     *
     * @test
     */
    public function anonymousCanGetListOfSettings()
    {
        $response = self::$client->get('/tenant/settings');
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
        $this->assertResponsePaginateList($response, 'Find result is not JSON paginated list');
    }

    /**
     * Getting list of properties with owner
     *
     * @test
     */
    public function ownerCanGetListOfSettings()
    {
        // Getting list
        $response = self::$ownerClient->get('/tenant/settings');
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
        $this->assertResponsePaginateList($response, 'Find result is not JSON paginated list');
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
        $response = self::$ownerClient->post('/tenant/settings', $values);
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');

        $setting = new Tenant_Setting();
        $list = $setting->getList();
        $this->assertTrue(sizeof($list) > 0, 'Setting is not created');
        $this->assertEquals($values['value'], Tenant_Service::setting($values['key']), 'Values are not equal.');
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
        $response = self::$ownerClient->post('/tenant/settings', $values);
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');

        $setting = new Tenant_Setting();
        $list = $setting->getList();
        $this->assertTrue(sizeof($list) > 0, 'Setting is not created');
        $this->assertEquals($values['value'], Tenant_Service::setting($values['key']), 'Values are not equal.');

        $response = self::$ownerClient->get('/tenant/settings/' . $values['key']);
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
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
        $response = self::$ownerClient->post('/tenant/settings', $values);
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');

        $setting = new Tenant_Setting();
        $list = $setting->getList();
        $this->assertTrue(sizeof($list) > 0, 'Setting is not created');
        $this->assertEquals($values['value'], Tenant_Service::setting($values['key']), 'Values are not equal.');

        $sql = new Pluf_SQL('`key`=%s', array(
            $values['key']
        ));
        $one = $setting->getOne(array(
            'filter' => $sql->gen()
        ));
        $this->assertNotNull($one, 'Setting not found with key');

        $response = self::$ownerClient->get('/tenant/settings/' . $one->id);
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
        // Getting list
        $values = array(
            'key' => 'KEY-TEST-' . rand(),
            'value' => 'NOT SET',
            'mode' => Tenant_Setting::MOD_PUBLIC
        );
        $response = self::$ownerClient->post('/tenant/settings', $values);
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');

        // Get setting form db
        $setting = new Tenant_Setting();
        $sql = new Pluf_SQL('`key`=%s', array(
            $values['key']
        ));
        $one = $setting->getOne(array(
            'filter' => $sql->gen()
        ));
        $this->assertNotNull($one, 'Setting not found with key');

        // delete by id
        $response = self::$ownerClient->delete('/tenant/settings/' . $one->id);
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');

        // Check if deleted
        $one = $setting->getOne(array(
            'filter' => $sql->gen()
        ));
        $this->assertNull($one, 'Setting is not deleted');
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
        $response = self::$ownerClient->post('/tenant/settings', $values);
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');

        $setting = new Tenant_Setting();
        $sql = new Pluf_SQL('`key`=%s', array(
            $values['key']
        ));
        $one = $setting->getOne(array(
            'filter' => $sql->gen()
        ));
        $this->assertNotNull($one, 'Setting not found with key');

        $values['value'] = 'new value' . rand();
        $response = self::$ownerClient->post('/tenant/settings/' . $one->id, $values);
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');

        $one = $setting->getOne(array(
            'filter' => $sql->gen()
        ));
        $this->assertNotNull($one, 'Setting not found with key');
        $this->assertEquals($values['value'], $one->value);
    }
}