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
class Setting_REST_BasicAnonymouseTest extends AbstractBasicTest
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
            ),
            array(
                'app' => 'Tenant',
                'regex' => '#^#',
                'sub' => include 'Tenant/urls-app-v2.php'
            )
        ));
        self::$client->clean(true);
    }

    /**
     * Getting list of properties
     *
     * @test
     * @expectedException Pluf_Exception_Unauthorized
     */
    public function anonymousCanGetListOfSettings()
    {
        $response = self::$client->get('/api/v2/tenant/resources');
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
        $this->assertResponsePaginateList($response, 'Find result is not JSON paginated list');
    }

    /**
     * Create a new setting in system
     *
     * @test
     * @expectedException Pluf_Exception_Unauthorized
     */
    public function anonymousCanntCreateAResource()
    {
        // Getting list
        $values = array(
            'path' => '/KEy/.TEST/' . rand(),
            'title' => 'NOT SET',
            'description' => 'This is a test resources'
        );
        $response = self::$client->post('/api/v2/tenant/resources', $values);
    }
    
    
    /**
     * Create a new setting in system
     *
     * @test
     */
    public function anonymousCanAccessResourceWithPath(){
        
        // create resource
        $r1 = new Tenant_Resource();
        $r1->path = '/path/to.test/resource-' . rand();
        $r1->title = 'A test resource';
        $r1->description = 'Is created automaticl';
        $r1->create();
        
        // upload resource value
        $testContent = 'test content:' . rand();
        $myfile = fopen($r1->getAbsloutPath(), "w") or die("Unable to open resource file!");
        fwrite($myfile, $testContent);
        fclose($myfile);
        $r1->update();
        
        $response = self::$client->get($r1->path);
        $this->assertEquals($response->status_code, 200);
        // FIXME: maso, 2019: check if the content value is match with test content
        // $this->assertEquals($testContent, $response, 'Value is not the same as input value');
        $r1->delete();
    }
}