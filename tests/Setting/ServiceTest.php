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
class Setting_ServiceTest extends AbstractBasicTest
{
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
        $setting->mode = Tenant_Setting::MOD_PUBLIC;
        Test_Assert::assertTrue($setting->create());
        
        $result = Tenant_Service::setting($key, 'New value');
        Test_Assert::assertNotNull($result, 'Failt to get non defined value');
        Test_Assert::assertEquals($value, $result, 'Value is not a defualt one');
    }
}