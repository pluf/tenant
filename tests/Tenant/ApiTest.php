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
class Tenant_ApiTest extends TestCase
{

    /**
     * @before
     */
    public function setUpTest ()
    {
        Pluf::start(__DIR__. '/../conf/config.php');
    }

    /**
     * @test
     */
    public function testClassInstance ()
    {
        $object = new Tenant_Tenant();
        $this->assertTrue(isset($object), 'Tenant_Tenant could not be created!');
        $object = new Tenant_BankBackend();
        $this->assertTrue(isset($object), 'Tenant_BankBackend could not be created!');
        $object = new Tenant_BankReceipt();
        $this->assertTrue(isset($object), 'Tenant_BankReceipt could not be created!');
        $object = new Tenant_Comment();
        $this->assertTrue(isset($object), 'Tenant_Comment could not be created!');
        $object = new Tenant_Configuration();
        $this->assertTrue(isset($object), 'Tenant_Configuration could not be created!');
        $object = new Tenant_Setting();
        $this->assertTrue(isset($object), 'Tenant_Setting could not be created!');
        $object = new Tenant_Invoice();
        $this->assertTrue(isset($object), 'Tenant_Invoice could not be created!');
        $object = new Tenant_Ticket();
        $this->assertTrue(isset($object), 'Tenant_Ticket could not be created!');
        $object = new Tenant_Resource();
        $this->assertTrue(isset($object), 'Tenant_Resource could not be created!');
        $object = new Tenant_SPA();
        $this->assertTrue(isset($object), 'Tenant_SPA could not be created!');
        $object = new Tenant_Member();
        $this->assertTrue(isset($object), 'Tenant_Member could not be created!');
        $object = new Tenant_SubtenantConfiguration();
        $this->assertTrue(isset($object), 'Tenant_SubtenantConfiguration could not be created!');
    }
}

