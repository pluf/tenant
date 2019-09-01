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
class Tenant_REST_TenantTest extends AbstractBasicTestMt
{

    private static function getApiV2()
    {
        $myAPI = array(
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
        );
        return $myAPI;
    }

    /**
     * Getting tenant info
     *
     * Call tenant to get current tenant information.
     *
     * @test
     */
    public function testDefaultTenant()
    {
        $client = new Test_Client(self::getApiV2());
        $response = $client->get('/api/v2/tenant/tenants/current');
        $this->assertNotNull($response);
        $this->assertEquals($response->status_code, 200);
    }

    /**
     * Getting tenant info with full informations
     *
     * @test
     */
    public function testGetDefaultTenantByGraphQl()
    {
        $client = new Test_Client(self::getApiV2());
        $response = $client->get('/api/v2/tenant/tenants/current', array(
            'graphql' => '{id, configurations{id}}'
        ));
        $this->assertNotNull($response);
        $this->assertEquals($response->status_code, 200);
    }

    /**
     *
     * @test
     */
    public function getSettingsOfCurrentTenantByGraphql()
    {
        $client = new Test_Client(self::getApiV2());
        $response = $client->get('/api/v2/tenant/tenants/current', array(
            'graphql' => '{id, settings{id}}'
        ));
        $this->assertNotNull($response);
        $this->assertEquals($response->status_code, 200);
    }

    /**
     *
     * @test
     */
    public function getsCurrentUserOfCurrentTenantByGraphql()
    {
        $client = new Test_Client(self::getApiV2());
        $response = $client->get('/api/v2/tenant/tenants/current', array(
            'graphql' => '{id, account{id,roles{id}, groups{id, roles{id}}}}'
        ));
        $this->assertNotNull($response);
        $this->assertEquals($response->status_code, 200);
    }

    /**
     *
     * @test
     */
    public function getsCurrentUserProfilesOfCurrentTenantByGraphql()
    {
        $client = new Test_Client(self::getApiV2());
        $response = $client->get('/api/v2/tenant/tenants/current', array(
            'graphql' => '{id, account{id,profiles{id}}}'
        ));
        $this->assertNotNull($response);
        $this->assertEquals($response->status_code, 200);
    }

    /**
     *
     * @test
     */
    public function getsCurrentUserMessagesOfCurrentTenantByGraphql()
    {
        $client = new Test_Client(self::getApiV2());
        $response = $client->get('/api/v2/tenant/tenants/current', array(
            'graphql' => '{id, account{id,messages{id}}}'
        ));
        $this->assertNotNull($response);
        $this->assertEquals($response->status_code, 200);
    }

    /**
     *
     * @test
     */
    public function getsFullOfCurrentTenantByGraphql()
    {
        $client = new Test_Client(self::getApiV2());
        $response = $client->get('/api/v2/tenant/tenants/current', array(
            'graphql' => '{id, account{id,roels{id}, groups{id}, profiles{id}},messages{id},settings{id},configurations{id}}'
        ));
        $this->assertNotNull($response);
        $this->assertEquals($response->status_code, 200);
    }

    /**
     * Getting tenants
     *
     * @test
     */
    public function getTenantsByOwner()
    {
        $client = new Test_Client(self::getApiV2());
        // 1- Login
        $response = $client->post('/api/v2/user/login', array(
            'login' => 'test',
            'password' => 'test'
        ));
        Test_Assert::assertResponseStatusCode($response, 200, 'Fail to login');

        // 2- getting list of tenants
        $response = $client->get('/api/v2/tenant/tenants');
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
        Test_Assert::assertResponsePaginateList($response, 'Find result is not JSON paginated list');
    }

    /**
     * Getting tenants
     *
     * @test
     * @expectedException Pluf_Exception_Unauthorized
     */
    public function getTenantsByAnonymous()
    {
        $client = new Test_Client(self::getApiV2());

        // 2- getting list of tenants
        $response = $client->get('/api/v2/tenant/tenants');
    }

    /**
     * Creates new tenant
     *
     * @test
     */
    public function createNewTenantByAdmin()
    {
        $client = new Test_Client(self::getApiV2());
        // 1- Login
        $response = $client->post('/api/v2/user/login', array(
            'login' => 'test',
            'password' => 'test'
        ));
        Test_Assert::assertResponseStatusCode($response, 200, 'Fail to login');

        // 2.0 data
        $subdomain = 'test' . rand();
        $data = array(
            'subdomain' => $subdomain,
            'domain' => $subdomain . '.domain.ir'
        );
        // 2- getting list of tenants
        $response = $client->post('/api/v2/tenant/tenants', $data);
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
        Test_Assert::assertResponseAsModel($response, 200, 'Fail to create tenant');

        $actual = json_decode($response->content, true);

        // 2- getting list of tenants
        $response = $client->get('/api/v2/tenant/tenants/' . $actual['id']);
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
        Test_Assert::assertResponseAsModel($response, 200, 'Fail to find created tenant');
    }

    /**
     *
     * @test
     */
    public function getConfigurationsOfCurrentTenant()
    {
        $client = new Test_Client(self::getApiV2());
        // 1- Login
        $response = $client->post('/api/v2/user/login', array(
            'login' => 'test',
            'password' => 'test'
        ));
        Test_Assert::assertResponseStatusCode($response, 200, 'Fail to login');

        // 2- getting list of tenants
        $response = $client->get('/api/v2/tenant/tenants/current/configurations');
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
    }

    /**
     *
     * @test
     */
    public function getConfigurationsOfSubTenant()
    {
        $client = new Test_Client(self::getApiV2());
        // 1- Login
        $response = $client->post('/api/v2/user/login', array(
            'login' => 'test',
            'password' => 'test'
        ));
        Test_Assert::assertResponseStatusCode($response, 200, 'Fail to login');

        // 2.0 data
        $subdomain = 'test' . rand();
        $data = array(
            'subdomain' => $subdomain,
            'domain' => $subdomain . '.domain.ir'
        );
        // 2- getting list of tenants
        $response = $client->post('/api/v2/tenant/tenants', $data);
        Test_Assert::assertResponseNotNull($response, 'Find result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Find status code is not 200');
        Test_Assert::assertResponseAsModel($response, 200, 'Fail to create tenant');

        $actual = json_decode($response->content, true);

        // 2- getting list of tenants
        $response = $client->get('/api/v2/tenant/tenants/' . $actual['id'] . '/configurations');
        Test_Assert::assertResponseNotNull($response, 'Collection result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Collection status code is not 200');
    }

    /**
     *
     * @test
     */
    public function getConfigurationsOfSubTenantByGraphql()
    {
        $client = new Test_Client(self::getApiV2());
        // 1- Login
        $response = $client->post('/api/v2/user/login', array(
            'login' => 'test',
            'password' => 'test'
        ));
        Test_Assert::assertResponseStatusCode($response, 200, 'Fail to login');

        // 2- getting list of tenants
        $response = $client->get('/api/v2/tenant/tenants', array(
            'graphql' => '{items{id, configurations{id}}}'
        ));
        Test_Assert::assertResponseNotNull($response, 'Collection result is empty');
        Test_Assert::assertResponseStatusCode($response, 200, 'Collection status code is not 200');
    }
}

