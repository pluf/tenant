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

class BasicsTest extends AbstractBasicTest
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
    }

    /**
     *
     * @test
     */
    public function listSapsRestTest()
    {
        $response = self::$client->get('/tenant/spas');
        $this->assertResponseNotNull($response, 'Find result is empty');
        $this->assertResponseStatusCode($response, 200, 'Find status code is not 200');
        $this->assertResponsePaginateList($response, 'Find result is not JSON paginated list');
    }
}



