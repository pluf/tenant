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
require_once 'Pluf.php';

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class Setting_REST_BasicTest extends TestCase
{

    private static $client = null;

    private static $user = null;

    /**
     * @beforeClass
     */
    public static function createDataBase()
    {
        Pluf::start(__DIR__ . '/../conf/config.php');
        $m = new Pluf_Migration(Pluf::f('installed_apps'));
        $m->install();
        
        // Test tenant
        $tenant = new Pluf_Tenant();
        $tenant->domain = 'localhost';
        $tenant->subdomain = 'www';
        $tenant->validate = true;
        if (true !== $tenant->create()) {
            throw new Pluf_Exception('Faile to create new tenant');
        }
        
        $m->init($tenant);
        
        // Test user
        $user = new User();
        $user->login = 'test';
        $user->first_name = 'test';
        $user->last_name = 'test';
        $user->email = 'toto@example.com';
        $user->setPassword('test');
        $user->active = true;
        
        if(!isset($GLOBALS['_PX_request'])){
            $GLOBALS['_PX_request'] = new Pluf_HTTP_Request('/');
        }
        $GLOBALS['_PX_request']->tenant= $tenant;
        if (true !== $user->create()) {
            throw new Exception();
        }
        
        $per = Role::getFromString('Pluf.owner');
        $user->setAssoc($per);
        
        $subs = include 'Tenant/urls.php';
        for ($i = 0; $i < sizeof($subs); $i++) {
            $subs[$i]['precond'] = array();
        }
        self::$client = new Test_Client(array(
            array(
                'app' => 'Tenant',
                'regex' => '#^/api/saas#',
                'base' => '',
                'sub' => $subs
            )
        ));
    }

    /**
     * @afterClass
     */
    public static function removeDatabses()
    {
        $m = new Pluf_Migration(Pluf::f('installed_apps'));
        $m->unInstall();
    }

    /**
     * Getting list of properties
     *
     * @test
     */
    public function anonymousCanGetListOfSettings()
    {
        $response = self::$client->get('/api/saas/setting/find');
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
        Test_Assert::assertResponsePaginateList($response, 'Find result is not JSON paginated list');
    }

    /**
     * Getting list of properties with admin
     *
     * @test
     */
    public function adminCanGetListOfSettings()
    {
        // Getting list
        $response = self::$client->get('/api/saas/setting/find');
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
        Test_Assert::assertResponsePaginateList($response, 'Find result is not JSON paginated list');
    }

    /**
     * Create a new setting in system
     *
     * @test
     */
    public function adminCanCreateASetting()
    {
        // Getting list
        $values = array(
            'key' => 'KEY-TEST-' . rand(),
            'value' => 'NOT SET',
            'mode' => Tenant_Setting::MOD_PUBLIC
        );
        $response = self::$client->post('/api/saas/setting/new', $values);
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
        
        $setting = new Tenant_Setting();
        $list = $setting->getList();
        Test_Assert::assertTrue(sizeof($list) > 0, 'Setting is not created');
        Test_Assert::assertEquals($values['value'], Tenant_Service::setting($values['key']), 'Values are not equal.');
    }

    /**
     * Create and update a new setting in system by admin
     *
     * @test
     */
    public function adminCanCreateAndGetSettingByKey()
    {
        // Getting list
        $values = array(
            'key' => 'KEY-TEST-' . rand(),
            'value' => 'NOT SET',
            'mode' => Tenant_Setting::MOD_PUBLIC
        );
        $response = self::$client->post('/api/saas/setting/new', $values);
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
        
        $setting = new Tenant_Setting();
        $list = $setting->getList();
        Test_Assert::assertTrue(sizeof($list) > 0, 'Setting is not created');
        Test_Assert::assertEquals($values['value'], Tenant_Service::setting($values['key']), 'Values are not equal.');
        
        $response = self::$client->get('/api/saas/setting/' . $values['key']);
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
    }

    /**
     * Create and update a new setting in system by admin
     *
     * @test
     */
    public function adminCanCreateAndGetSettingById()
    {
        // Getting list
        $values = array(
            'key' => 'KEY-TEST-' . rand(),
            'value' => 'NOT SET',
            'mode' => Tenant_Setting::MOD_PUBLIC
        );
        $response = self::$client->post('/api/saas/setting/new', $values);
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
        
        $response = self::$client->get('/api/saas/setting/' . $one->id);
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
    }

    /**
     * Create and update a new setting in system by admin
     *
     * @test
     */
    public function adminCanCreateAndDeleteSettingById()
    {
        // Getting list
        $values = array(
            'key' => 'KEY-TEST-' . rand(),
            'value' => 'NOT SET',
            'mode' => Tenant_Setting::MOD_PUBLIC
        );
        $response = self::$client->post('/api/saas/setting/new', $values);
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
        $response = self::$client->delete('/api/saas/setting/' . $one->id);
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
        
        // Check if deleted
        $one = $setting->getOne(array(
            'filter' => $sql->gen()
        ));
        Test_Assert::assertNull($one, 'Setting is not deleted');
    }

    /**
     * Create and update a new setting in system by admin
     *
     * @test
     */
    public function adminCanCreateAndUpdateSettingById()
    {
        // Getting list
        $values = array(
            'key' => 'KEY-TEST-' . rand(),
            'value' => 'NOT SET',
            'mode' => Tenant_Setting::MOD_PUBLIC
        );
        $response = self::$client->post('/api/saas/setting/new', $values);
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
        $response = self::$client->post('/api/saas/setting/' . $one->id, $values);
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
        
        $one = $setting->getOne(array(
            'filter' => $sql->gen()
        ));
        Test_Assert::assertNotNull($one, 'Setting not found with key');
        Test_Assert::assertEquals($values['value'], $one->value);
    }
}