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
class Setting_ServiceTest extends TestCase
{

    /**
     * @beforeClass
     */
    public static function createDataBase()
    {
        Pluf::start(__DIR__ . '/../conf/config.php');
        $m = new Pluf_Migration(array(
            'Pluf',
            'User',
            'Role',
            'Group',
            'Tenant'
        ));
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
    }

    /**
     * @afterClass
     */
    public static function removeDatabses()
    {
        $m = new Pluf_Migration(array(
            'Pluf',
            'User',
            'Role',
            'Group',
            'Tenant'
        ));
        $m->unInstall();
    }

    /**
     * Getting list of properties
     *
     * @test
     */
    public function shouldPossibleToGetNotDefinedProperty()
    {
        $result = Tenant_Service::setting('undefined-key', 'value');
        Test_Assert::assertNotNull($result, 'Failt to get non defined value');
        Test_Assert::assertEquals('value', $result, 'Value is not a defualt one');
    }

    /**
     * @test
     */
    public function shouldUseFirstValueAsInital()
    {
        $key = 'undefined-key';
        $result = Tenant_Service::setting($key, 'value1');
        Test_Assert::assertNotNull($result, 'Failt to get non defined value');
        Test_Assert::assertEquals('value', $result, 'Value is not a defualt one');
        
        $result2 = Tenant_Service::setting($key, 'value2');
        Test_Assert::assertEquals($result, $result2, 'Value is not a defualt one');
    }

    /**
     * @test
     */
    public function flushMustPushDataToDB()
    {
        $key = 'undefined-key-' . rand();
        $value = 'value';
        $result = Tenant_Service::setting($key, $value);
        Test_Assert::assertNotNull($result, 'Failt to get non defined value');
        Test_Assert::assertEquals('value', $result, 'Value is not a defualt one');
        
        Tenant_Service::flush();
        
        $setting = new Tenant_Setting();
        $sql = new Pluf_SQL('`key`=%s', array(
            $key
        ));
        $one = $setting->getOne(array(
            'filter' => $sql->gen()
        ));
        Test_Assert::assertNotNull($one, 'Setting not found with key');
        Test_Assert::assertEquals($value, $one->value, 'value are not the same');
    }

    /**
     * @test
     */
    public function shouldUsePreSavedSetting()
    {
        $key = 'undefined-key-' . rand();
        $value = 'value' . rand();
        
        // Create setting
        $setting = new Tenant_Setting();
        $setting->key = $key;
        $setting->value = $value;
        $setting->mod = Tenant_Setting::MOD_PUBLIC;
        Test_Assert::assertTrue($setting->create());
        
        $result = Tenant_Service::setting($key, 'New value');
        Test_Assert::assertNotNull($result, 'Failt to get non defined value');
        Test_Assert::assertEquals($value, $result, 'Value is not a defualt one');
    }
}