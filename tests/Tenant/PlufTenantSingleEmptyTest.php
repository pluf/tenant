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
use PHPUnit\Framework\IncompleteTestError;
require_once 'Pluf.php';

/**
 * @backupGlobals disabled
 * @backupStaticAttributes disabled
 */
class PlufTenantSingleEmptyTest extends TestCase
{

    /**
     * @beforeClass
     */
    public static function installApps()
    {
        $cfg = include __DIR__ . '/../conf/config.php';
        $cfg['multitenant'] = false;
        Pluf::start($cfg);
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
        
        if (! isset($GLOBALS['_PX_request'])) {
            $GLOBALS['_PX_request'] = new Pluf_HTTP_Request('/');
        }
        $GLOBALS['_PX_request']->tenant = $tenant;
        if (true !== $user->create()) {
            throw new Exception();
        }
        
        $per = Role::getFromString('Pluf.owner');
        $user->setAssoc($per);
    }

    /**
     * @afterClass
     */
    public static function uninstallApps()
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
     * @test
     */
    public function testDefaultTenant()
    {
        $tenant = Pluf_Tenant::current();
        $this->assertNotNull($tenant);
        
        // check id
        $id = $tenant->id;
        $this->assertNotNull($id);
        
        // check title
        $title = $tenant->title;
        $this->assertNotNull($title);
        
        // check description
        $desc = $tenant->description;
        $this->assertNotNull($desc);
    }

    /**
     * @test
     */
    public function testStoragePath()
    {
        $tenant = Pluf_Tenant::current();
        $this->assertNotNull($tenant);
        
        $storage = $tenant->storagePath();
        $this->assertNotNull($storage);
        $this->assertEquals(Pluf::f('upload_path'), $storage);
    }
}


