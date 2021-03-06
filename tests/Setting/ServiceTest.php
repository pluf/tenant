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

use Pluf\Test\Base\AbstractBasicTest;
use Pluf_SQL;
use Tenant_Service;
use Tenant_Setting;

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
        $this->assertNotNull($result, 'Failt to get non defined value');
        $this->assertEquals('value', $result, 'Value is not a defualt one');
    }

    /**
     *
     * @test
     */
    public function shouldUseFirstValueAsInital()
    {
        $key = 'undefined-key';
        $result = Tenant_Service::setting($key, 'value1');
        $this->assertNotNull($result, 'Failt to get non defined value');
        $this->assertEquals('value', $result, 'Value is not a defualt one');

        $result2 = Tenant_Service::setting($key, 'value2');
        $this->assertEquals($result, $result2, 'Value is not a defualt one');
    }

    /**
     *
     * @test
     */
    public function flushMustPushDataToDB()
    {
        $key = 'undefined-key-' . rand();
        $value = 'value';
        $result = Tenant_Service::setting($key, $value);
        $this->assertNotNull($result, 'Failt to get non defined value');
        $this->assertEquals('value', $result, 'Value is not a defualt one');

        Tenant_Service::flush();

        $setting = new Tenant_Setting();
        $sql = new Pluf_SQL('`key`=%s', array(
            $key
        ));
        $one = $setting->getOne(array(
            'filter' => $sql->gen()
        ));
        $this->assertNotNull($one, 'Setting not found with key');
        $this->assertEquals($value, $one->value, 'value are not the same');
    }

    /**
     *
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
        $this->assertTrue($setting->create());

        $result = Tenant_Service::setting($key, 'New value');
        $this->assertNotNull($result, 'Failt to get non defined value');
        $this->assertEquals($value, $result, 'Value is not a defualt one');
    }
}