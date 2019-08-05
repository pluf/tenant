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
    // **************************************************************** Current Tenant
    array( // Get current tenant
        'regex' => '#^/tenants/current$#',
        'model' => 'Tenant_Views',
        'method' => 'getCurrent',
        'http-method' => 'GET',
        'precond' => array()
    ),
    // **************************************************************** Sub tenants
    array( // get list of all tentnats
        'regex' => '#^/tenants$#',
        'model' => 'Tenant_Views',
        'method' => 'getTenants',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    array( // Add a new tenant
        'regex' => '#^/tenants$#',
        'model' => 'Tenant_Views',
        'method' => 'putTenant',
        'http-method' => 'POST',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    array( // Gets a tenant
        'regex' => '#^/tenants/(?P<modelId>\d+)$#',
        'model' => 'Tenant_Views',
        'method' => 'getTenant',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    array( // Updates a tenant
        'regex' => '#^/tenants/(?P<modelId>\d+)$#',
        'model' => 'Tenant_Views',
        'method' => 'updateTenant',
        'http-method' => 'POST',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    array( // Delete a tenant
        'regex' => '#^/tenants/(?P<modelId>\d+)$#',
        'model' => 'Tenant_Views',
        'method' => 'deleteTenant',
        'http-method' => 'DELETE',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    // **************************************************************** Configurations
    // XXX: maso, 2019
    // **************************************************************** Settings
    // XXX: maso, 2019
    // **************************************************************** Tickets
    // XXX: maso, 2019
    // **************************************************************** Members
    // XXX: maso, 2019
    // **************************************************************** Invoices
    // XXX: maso, 2019
);

