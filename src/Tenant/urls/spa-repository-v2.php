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
    
    array(
        'regex' => '#^/spa-repositories/default/spas/(?P<modelId>.+)/states$#',
        'model' => 'Tenant_Views_SpaRepository',
        'method' => 'findRemoteSpaStates',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    array(
        'regex' => '#^/spa-repositories/default/spas/(?P<modelId>.+)/states/(?P<stateId>.+)$#',
        'model' => 'Tenant_Views_SpaRepository',
        'method' => 'getRemoteSpaState',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    array(
        'regex' => '#^/spa-repositories/default/spas/(?P<modelId>\d+)/states/(?P<stateId>.+)$#',
        'model' => 'Tenant_Views_SpaRepository',
        'method' => 'putToRemoteSpaState',
        'http-method' => array(
            'PUT',
            'POST'
        ),
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    
    array(
        'regex' => '#^/spa-repositories/default/spas$#',
        'model' => 'Tenant_Views_SpaRepository',
        'method' => 'find',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    array(
        'regex' => '#^/spa-repositories/default/spas/(?P<modelId>.+)$#',
        'model' => 'Tenant_Views_SpaRepository',
        'method' => 'get',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    )
);
