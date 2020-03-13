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
namespace Pluf\Test\Spa\REST;

use Pluf\Test\Client;
use Pluf\Test\Base\AbstractBasicTest;
use Tenant_Service;
use Tenant_SpaService;

class BaseTagTest extends AbstractBasicTest
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
        self::$client = new Client();

        // default spa
        $path = dirname(__FILE__) . '/../resources/testDefault.zip';
        Tenant_Service::setSetting('spa.default', 'testDefault');
        Tenant_SpaService::installFromFile($path);
        $path = dirname(__FILE__) . '/../resources/testResource.zip';
        Tenant_SpaService::installFromFile($path);
    }

    /**
     *
     * @test
     */
    public function getMainFileOfDefaultSpa()
    {
        $response = self::$client->get('/testResource/');
        $this->assertResponseNotNull($response, 'Fail to load main file of default tenant');
        $this->assertResponseStatusCode($response, 200, 'Result status code is not 200');
        $this->assertEquals('<html><h1>test_spa</h1></html>', $response->content, 'Base tag replacement is not correct');
    }

    /**
     *
     * @test
     */
    public function getFakeFileOfDefaultSpa()
    {
        $response = self::$client->get('/testResource/alaki/path');
        $this->assertResponseNotNull($response, 'Fail to load main file of default tenant');
        $this->assertResponseStatusCode($response, 200, 'Result status code is not 200');
        $this->assertEquals('<html><h1>test_spa</h1></html>', $response->content, 'Base tag replacement is not correct');
    }

    /**
     *
     * @test
     */
    public function getMainFileOfSpa()
    {
        $response = self::$client->get('/testDefault/');
        $this->assertResponseNotNull($response, 'Fail to load main file of default tenant');
        $this->assertResponseStatusCode($response, 200, 'Result status code is not 200');
        $this->assertEquals('<base href="/testDefault/">', $response->content, 'Base tag replacement is not correct');
    }

    /**
     *
     * @test
     */
    public function getFakeFileOfSpa()
    {
        $response = self::$client->get('/testDefault/alaki/path');
        $this->assertResponseNotNull($response, 'Fail to load main file of default tenant');
        $this->assertResponseStatusCode($response, 200, 'Result status code is not 200');
        $this->assertEquals('<base href="/testDefault/">', $response->content, 'Base tag replacement is not correct');
    }
}


