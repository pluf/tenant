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
namespace Pluf\Test\Tenant\Rest;

use Pluf\Test\Client;
use Pluf\Test\Base\AbstractBasicTestMt;

class TenantTest extends AbstractBasicTestMt
{

    /**
     * Getting tenant info
     *
     * Call tenant to get current tenant information.
     *
     * @test
     */
    public function testDefaultTenant()
    {
        $client = new Client();
        $response = $client->get('/tenant/tenants/current');
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
        $client = new Client();
        $response = $client->get('/tenant/tenants/current', array(
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
        $client = new Client();
        $response = $client->get('/tenant/tenants/current', array(
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
        $client = new Client();
        $response = $client->get('/tenant/tenants/current', array(
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
        $client = new Client();
        $response = $client->get('/tenant/tenants/current', array(
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
        $client = new Client();
        $response = $client->get('/tenant/tenants/current', array(
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
        $client = new Client();
        $response = $client->get('/tenant/tenants/current', array(
            'graphql' => '{id, account{id,roles{id}, groups{id}, profiles{id}},settings{id},configurations{id}}'
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
        $client = new Client();
        // 1- Login
        $response = $client->post('/user/login', array(
            'login' => 'test',
            'password' => 'test'
        ));
        $this->assertResponseStatusCode($response, 200, 'Fail to login');

        // 2- getting list of tenants
        $response = $client->get('/tenant/tenants');
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
        $this->assertResponsePaginateList($response, 'Find result is not JSON paginated list');
    }

    /**
     * Getting tenants
     *
     * @test
     * @expectedException Pluf_Exception_Unauthorized
     */
    public function getTenantsByAnonymous()
    {
        $client = new Client();

        // 2- getting list of tenants
        $client->get('/tenant/tenants');
    }

    /**
     * Creates new tenant
     *
     * @test
     */
    public function createNewTenantByAdmin()
    {
        $client = new Client();
        // 1- Login
        $response = $client->post('/user/login', array(
            'login' => 'test',
            'password' => 'test'
        ));
        $this->assertResponseStatusCode($response, 200, 'Fail to login');

        // 2.0 data
        $subdomain = 'test' . rand();
        $data = array(
            'subdomain' => $subdomain,
            'domain' => $subdomain . '.domain.ir'
        );
        // 2- getting list of tenants
        $response = $client->post('/tenant/tenants', $data);
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
        $this->assertResponseAsModel($response, 200, 'Fail to create tenant');

        $actual = json_decode($response->content, true);

        // 2- getting list of tenants
        $response = $client->get('/tenant/tenants/' . $actual['id']);
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
        $this->assertResponseAsModel($response, 200, 'Fail to find created tenant');
    }

    /**
     *
     * @test
     */
    public function getConfigurationsOfCurrentTenant()
    {
        $client = new Client();
        // 1- Login
        $response = $client->post('/user/login', array(
            'login' => 'test',
            'password' => 'test'
        ));
        $this->assertResponseStatusCode($response, 200, 'Fail to login');

        // 2- getting list of tenants
        $response = $client->get('/tenant/tenants/current/configurations');
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
    }

    /**
     *
     * @test
     */
    public function getConfigurationsOfSubTenant()
    {
        $client = new Client();
        // 1- Login
        $response = $client->post('/user/login', array(
            'login' => 'test',
            'password' => 'test'
        ));
        $this->assertResponseStatusCode($response, 200, 'Fail to login');

        // 2.0 data
        $subdomain = 'test' . rand();
        $data = array(
            'subdomain' => $subdomain,
            'domain' => $subdomain . '.domain.ir'
        );
        // 2- getting list of tenants
        $response = $client->post('/tenant/tenants', $data);
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
        $this->assertResponseAsModel($response, 200, 'Fail to create tenant');

        $actual = json_decode($response->content, true);

        // 2- getting list of tenants
        $response = $client->get('/tenant/tenants/' . $actual['id'] . '/configurations');
        $this->assertResponseNotNull($response, 'Collection result is empty');
        $this->assertResponseStatusCode($response, 200, 'Collection status code is not 200');
    }

    /**
     *
     * @test
     */
    public function getConfigurationsOfSubTenantByGraphql()
    {
        $client = new Client();
        // 1- Login
        $response = $client->post('/user/login', array(
            'login' => 'test',
            'password' => 'test'
        ));
        $this->assertResponseStatusCode($response, 200, 'Fail to login');

        // 2- getting list of tenants
        $response = $client->get('/tenant/tenants', array(
            'graphql' => '{items{id, configurations{id}}}'
        ));
        $this->assertResponseNotNull($response, 'Collection result is empty');
        $this->assertResponseStatusCode($response, 200, 'Collection status code is not 200');
    }
}

