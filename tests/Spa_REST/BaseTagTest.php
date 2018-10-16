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
use PHPUnit\Framework\IncompleteTestError;
require_once 'Pluf.php';

set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../Base/');

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class Spa_REST_BaseTagTest extends AbstractBasicTest
{

    private static $client = null;
    
    /**
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
            ),
            array(
                'app' => 'Tenant',
                'regex' => '#^#',
                'base' => '',
                'sub' => include 'Tenant/urls-app-v2.php'
            )
        ));
            
        // default spa
        $path = dirname(__FILE__) . '/../resources/testDefault.zip';
        Tenant_Service::setSetting('spa.default', 'testDefault');
        Tenant_SpaService::installFromFile($path);
        $path = dirname(__FILE__) . '/../resources/testResource.zip';
        Tenant_SpaService::installFromFile($path);
    }

    /**
     * @test
     */
    public function getMainFileOfDefaultSpa()
    {
        $response = self::$client->get('/');
        Test_Assert::assertResponseNotNull($response, 'Fail to load main file of default tenant');
        Test_Assert::assertResponseStatusCode($response, 200, 'Result status code is not 200');
        Test_Assert::assertEquals('<base href="/">', $response->content, 'Base tag replacement is not correct');
    }
    
    /**
     * @test
     */
    public function getFakeFileOfDefaultSpa()
    {
        $response = self::$client->get('/alaki/path');
        Test_Assert::assertResponseNotNull($response, 'Fail to load main file of default tenant');
        Test_Assert::assertResponseStatusCode($response, 200, 'Result status code is not 200');
        Test_Assert::assertEquals('<base href="/">', $response->content, 'Base tag replacement is not correct');
    }
    
    /**
     * @test
     */
    public function getMainFileOfSpa()
    {
        $response = self::$client->get('/testDefault/');
        Test_Assert::assertResponseNotNull($response, 'Fail to load main file of default tenant');
        Test_Assert::assertResponseStatusCode($response, 200, 'Result status code is not 200');
        Test_Assert::assertEquals('<base href="/testDefault/">', $response->content, 'Base tag replacement is not correct');
    }
    
    /**
     * @test
     */
    public function getFakeFileOfSpa()
    {
        $response = self::$client->get('/testDefault/alaki/path');
        Test_Assert::assertResponseNotNull($response, 'Fail to load main file of default tenant');
        Test_Assert::assertResponseStatusCode($response, 200, 'Result status code is not 200');
        Test_Assert::assertEquals('<base href="/testDefault/">', $response->content, 'Base tag replacement is not correct');
    }
    
}



