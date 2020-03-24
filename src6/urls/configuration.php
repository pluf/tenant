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
return array(
    // **************************************************************** Configurations of a Tenant
    array( // schema
        'regex' => '#^/configurations/schema$#',
        'model' => 'Tenant_Views_Configuration',
        'method' => 'getSchema',
        'http-method' => 'GET',
        'params' => array(
            'model' => 'Tenant_Configuration'
        )
    ),
    array( // Read (list)
        'regex' => '#^/configurations$#',
        'model' => 'Tenant_Views_Configuration',
        'method' => 'findObject',
        'http-method' => 'GET',
        'params' => array(
            'model' => 'Tenant_Configuration',
            'listFilters' => array(
                'id',
                'key',
                'value'
            ),
            'listDisplay' => array(),
            'searchFields' => array(
                'key',
                'value',
                'description'
            ),
            'sortFields' => array(
                'id',
                'key',
                'value'
            ),
            'sortOrder' => array(
                'id',
                'DESC'
            )
        )
    ),
    array( // Read
        'regex' => '#^/configurations/(?P<modelId>\d+)$#',
        'model' => 'Tenant_Views_Configuration',
        'method' => 'getObject',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    array( // Read (by key)
        'regex' => '#^/configurations/(?P<key>[^/]+)$#',
        'model' => 'Tenant_Views_Configuration',
        'method' => 'get',
        'http-method' => 'GET'
    )
);



