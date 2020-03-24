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

use Pluf\Test\Base\AbstractBasicTestMt;
use Tenant_Invoice;
use Pluf\Test\Client;

class InvoicesTest extends AbstractBasicTestMt
{

    /**
     * Getting invoice list
     *
     * @test
     */
    public function shouldSupportMultipleLogin()
    {
        // login
        for ($i = 0; $i < 10; $i ++) {
            $client = new Client();
            $response = $client->post('/user/login', array(
                'login' => 'test',
                'password' => 'test'
            ));
            $this->assertResponseStatusCode($response, 200, 'Fail to login');
            // Current user is valid
            $response = $client->get('/user/accounts/current');
            $this->assertResponseStatusCode($response, 200, 'Fail to login');
            $this->assertResponseNotAnonymousModel($response, 'Current user is anonymous');
        }
    }

    /**
     * Getting invoice list
     *
     * @test
     */
    public function testFindInvoices()
    {
        $client = new Client();

        // login
        $response = $client->post('/user/login', array(
            'login' => 'test',
            'password' => 'test'
        ));
        $this->assertResponseStatusCode($response, 200, 'Fail to login');

        // Current user is valid
        $response = $client->get('/user/accounts/current');
        $this->assertResponseStatusCode($response, 200, 'Fail to login');
        $this->assertResponseNotAnonymousModel($response, 'Current user is anonymous');

        // find
        $response = $client->get('/tenant/invoices');
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
        $this->assertResponsePaginateList($response, 'Find result is not JSON paginated list');
    }

    /**
     * Getting invoice list
     *
     * Check non-empty list
     *
     * @test
     */
    public function testFindInvoicesNonEmpty()
    {
        $client = new Client();
        // login
        $response = $client->post('/user/login', array(
            'login' => 'test',
            'password' => 'test'
        ));
        $this->assertResponseStatusCode($response, 200, 'Fail to login');

        // Current user is valid
        $response = $client->get('/user/accounts/current');
        $this->assertResponseStatusCode($response, 200, 'Fail to login');
        $this->assertResponseNotAnonymousModel($response, 'Current user is anonymous');

        $i = new Tenant_Invoice();
        $i->title = 'test';
        $i->descscription = 'test';
        $i->amount = 1000;
        $i->due_dtime = gmdate('Y-m-d H:i:s');
        $i->create();

        // find
        $response = $client->get('/tenant/invoices');
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
        $this->assertResponsePaginateList($response, 'Find result is not JSON paginated list');
        $this->assertResponseNonEmptyPaginateList($response, 'No object is in list');

        // delete
        $i->delete();
    }

    /**
     * Getting invoice
     *
     * @test
     */
    public function testGetInvoice()
    {
        $client = new Client();
        // login
        $response = $client->post('/user/login', array(
            'login' => 'test',
            'password' => 'test'
        ));
        $this->assertResponseStatusCode($response, 200, 'Fail to login');

        $i = new Tenant_Invoice();
        $i->title = 'test';
        $i->descscription = 'test';
        $i->amount = 1000;
        $i->due_dtime = gmdate('Y-m-d H:i:s');
        $i->create();

        // find
        $response = $client->get('/tenant/invoices/' . $i->id);
        $this->assertResponseNotNull($response);
        $this->assertResponseStatusCode($response, 200);
        $this->assertResponseNotAnonymousModel($response, 'Invoice not foudn');

        // delete
        $i->delete();
    }

    /**
     * Getting invoice schema
     *
     * @test
     */
    public function testGetInvoiceSchema()
    {
        $client = new Client();
        $response = $client->get('/tenant/invoices/schema');
        $this->assertResponseNotNull($response);
        $this->assertResponseStatusCode($response, 200);
    }
}

