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
namespace Pluf\Test\Tenant;

use Pluf\Test\Base\AbstractBasicTest;

class PlufTenantSingleEmptyTest extends AbstractBasicTest
{

    /**
     *
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
     *
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


