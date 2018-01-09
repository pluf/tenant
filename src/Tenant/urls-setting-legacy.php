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
    // TODO: maso, 2017: some attributes are not readable by users
    array(
        'regex' => '#^/find$#',
        'model' => 'Tenant_Views_Setting',
        'method' => 'findObject',
        'http-method' => 'GET',
        'precond' => array(),
        'params' => array(
            'model' => 'Tenant_Setting',
            'listFilters' => array(
                'id',
                'key',
                'value',
                'description'
            ),
            'listDisplay' => array(
                'key' => 'key',
                'description' => 'description'
            ),
            'searchFields' => array(
                'title',
                'symbol',
                'description'
            ),
            'sortFields' => array(
                'title',
                'symbol',
                'description',
                'creation_date',
                'modif_dtime'
            )
        )
    ),
    array(
        'regex' => '#^/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'getObject',
        'http-method' => 'GET',
        'params' => array(
            'model' => 'Tenant_Setting'
        )
    ),
    array(
        'regex' => '#^/(?P<key>[^/]+)$#',
        'model' => 'Tenant_Views_Setting',
        'method' => 'get',
        'http-method' => 'GET'
    ),
    array(
        'regex' => '#^/(?P<modelId>[^/]+)$#',
        'model' => 'Pluf_Views',
        'method' => 'deleteObject',
        'http-method' => 'DELETE',
        'params' => array(
            'model' => 'Tenant_Setting'
        ),
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    array(
        'regex' => '#^/new$#',
        'model' => 'Pluf_Views',
        'method' => 'createObject',
        'http-method' => 'POST',
        'params' => array(
            'model' => 'Tenant_Setting'
        ),
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    array(
        'regex' => '#^/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'updateObject',
        'http-method' => 'POST',
        'params' => array(
            'model' => 'Tenant_Setting'
        ),
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    )
);
