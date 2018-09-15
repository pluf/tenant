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
    
    // ************************************************************ SPA
    array( // Create
        'regex' => '#^/spas$#',
        'model' => 'Tenant_Views_Spa',
        'method' => 'create',
        'http-method' => 'POST',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    array( // Read (list)
        'regex' => '#^/spas$#',
        'model' => 'Pluf_Views',
        'method' => 'findObject',
        'http-method' => 'GET',
        'precond' => array(),
        'params' => array(
            'model' => 'Tenant_SPA',
            'listFilters' => array(
                'id',
                'title',
                'symbol'
            ),
            'listDisplay' => array(
                'id' => 'spa id',
                'title' => 'title',
                'creation_dtime' => 'creation time'
            ),
            '$searchFields' => array(
                'name',
                'title',
                'description',
                'homepage'
            ),
            'sortFields' => array(
                'id',
                'name',
                'title',
                'homepage',
                'license',
                'version',
                'creation_dtime'
            ),
            'sortOrder' => array(
                'creation_dtime',
                'DESC'
            )
        )
    ),
    array( // Read
        'regex' => '#^/spas/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'getObject',
        'http-method' => 'GET',
        'precond' => array(),
        'params' => array(
            'model' => 'Tenant_SPA'
        )
    ),
    array( // Update
        'regex' => '#^/spas/(?P<modelId>\d+)$#',
        'model' => 'Tenant_Views_Spa',
        'method' => 'update',
        'http-method' => 'POST',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    array( // Delete
        'regex' => '#^/spas/(?P<spaId>\d+)$#',
        'model' => 'Tenant_Views_Spa',
        'method' => 'delete',
        'http-method' => 'DELETE',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    
    // ************************************************************ Transitions of SPAs (States of SPAs)
    
    array(
        'regex' => '#^/spas/(?P<modelId>\d+)/possible-transitions$#',
        'model' => 'Tenant_Views_SpaStates',
        'method' => 'find',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    array(
        'regex' => '#^/spas/(?P<modelId>\d+)/possible-transitions/(?P<transitionId>.+)$#',
        'model' => 'Tenant_Views_SpaStates',
        'method' => 'get',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    array(
        'regex' => '#^/spas/(?P<modelId>\d+)/transitions$#',
        'model' => 'Tenant_Views_SpaStates',
        'method' => 'put',
        'http-method' => array(
            'PUT',
            'POST'
        ),
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    
    // ************************************************************ Resources of SPAs
    
    array(
        'regex' => '#^/spas/(?P<modelId>\d+)/resources/find$#',
        'model' => 'Tenant_Views_SpaResources',
        'method' => 'find',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    array(
        'regex' => '#^/spas/(?P<modelId>\d+)/resources/new$#',
        'model' => 'Tenant_Views_SpaResources',
        'method' => 'create',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    array(
        'regex' => '#^/spas/(?P<modelId>\d+)/resources/(?P<resourcePath>.+)$#',
        'model' => 'Tenant_Views_SpaResources',
        'method' => 'get',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    array(
        'regex' => '#^/spas/(?P<modelId>\d+)/resources/(?P<resourcePath>.+)$#',
        'model' => 'Tenant_Views_SpaResources',
        'method' => 'update',
        'http-method' => 'POST',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    array(
        'regex' => '#^/spas/(?P<modelId>\d+)/resources/(?P<resourcePath>.+)$#',
        'model' => 'Tenant_Views_SpaResources',
        'method' => 'delete',
        'http-method' => 'Delete',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    
);
