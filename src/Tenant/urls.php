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
    /*
     * Tenant
     */
    array(
        'regex' => '#^/current$#',
        'model' => 'Tenant_Views',
        'method' => 'current',
        'http-method' => 'GET',
        'precond' => array()
    ),
    array(
        'regex' => '#^/current$#',
        'model' => 'Tenant_Views',
        'method' => 'update',
        'http-method' => 'POST',
        'precond' => array(
            'Pluf_Precondition::ownerRequired'
        )
    ),
    array(
        'regex' => '#^/current$#',
        'model' => 'Tenant_Views',
        'method' => 'delete',
        'http-method' => 'DELETE',
        'precond' => array(
            'Pluf_Precondition::ownerRequired'
        )
    ),
    /*
     * Ticket
     */
    array(
        'regex' => '#^/current/ticket/find$#',
        'model' => 'Pluf_Views',
        'method' => 'findObject',
        'http-method' => 'GET',
        'precond' => array(
            'Pluf_Precondition::ownerRequired'
        ),
        'params' => array(
            'model' => 'Tenant_Ticket',
            'listFilters' => array(
                'status',
                'type',
                'requester'
            ),
            'listDisplay' => array(),
            'searchFields' => array(
                'subject',
                'description'
            ),
            'sortFields' => array(
                'id',
                'status',
                'type',
                'modif_dtime',
                'creation_dtime'
            ),
            'sortOrder' => array(
                'id',
                'DESC'
            )
        )
    ),
    array(
        'regex' => '#^/current/ticket/new$#',
        'model' => 'Pluf_Views',
        'method' => 'createObject',
        'http-method' => 'POST',
        'precond' => array(
            'Pluf_Precondition::ownerRequired'
        ),
        'params' => array(
            'model' => 'Tenant_Ticket'
        )
    ),
    array(
        'regex' => '#^/current/ticket/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'getObject',
        'http-method' => 'GET',
        'precond' => array(
            'Pluf_Precondition::ownerRequired'
        ),
        'params' => array(
            'model' => 'Tenant_Ticket'
        )
    ),
    array(
        'regex' => '#^/current/ticket/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'updateObject',
        'http-method' => 'POST',
        'precond' => array(
            'Pluf_Precondition::ownerRequired'
        ),
        'params' => array(
            'model' => 'Tenant_Ticket'
        )
    ),
    array(
        'regex' => '#^/current/ticket/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'deleteObject',
        'http-method' => 'DELETE',
        'precond' => array(
            'Pluf_Precondition::ownerRequired'
        ),
        'params' => array(
            'model' => 'Tenant_Ticket'
        )
    ),
    /*
     * Comments of ticket
     */
    array(
        'regex' => '#^/current/ticket/(?P<tecket_id>\d+)/comment/find$#',
        'model' => 'Tenant_Views_Ticket',
        'method' => 'findComments',
        'http-method' => 'GET',
        'precond' => array(
            'Pluf_Precondition::ownerRequired'
        )
    ),
    array(
        'regex' => '#^/current/ticket/(?P<tecket_id>\d+)/comment/new$#',
        'model' => 'Tenant_Views_Ticket',
        'method' => 'createComments',
        'http-method' => 'POST',
        'precond' => array(
            'Pluf_Precondition::ownerRequired'
        )
    ),
    array(
        'regex' => '#^/current/ticket/(?P<tecket_id>\d+)/comment/(?P<comment_id>\d+)$#',
        'model' => 'Tenant_Views_Ticket',
        'method' => 'get',
        'http-method' => 'GET',
        'precond' => array(
            'Pluf_Precondition::ownerRequired'
        )
    ),
    array(
        'regex' => '#^/current/ticket/(?P<tecket_id>\d+)/comment/(?P<comment_id>\d+)$#',
        'model' => 'Tenant_Views_Ticket',
        'method' => 'get',
        'http-method' => 'POST',
        'precond' => array(
            'Pluf_Precondition::ownerRequired'
        )
    ),
    array(
        'regex' => '#^/current/ticket/(?P<tecket_id>\d+)/comment/(?P<comment_id>\d+)$#',
        'model' => 'Tenant_Views_Ticket',
        'method' => 'get',
        'http-method' => 'DELETE',
        'precond' => array(
            'Pluf_Precondition::ownerRequired'
        )
    ),
    
    /*
     * invoices
     */    
    array(
        'regex' => '#^/current/invoice/find$#',
        'model' => 'Tenant_Views_Invoices',
        'method' => 'find',
        'http-method' => 'GET',
        'precond' => array(
            'Pluf_Precondition::ownerRequired'
        )
    ),
    array(
        'regex' => '#^/current/invoice/new$#',
        'model' => 'Tenant_Views_Invoices',
        'method' => 'create',
        'http-method' => 'POST',
        'precond' => array(
            'Pluf_Precondition::ownerRequired'
        )
    ),
    array(
        'regex' => '#^/current/invoice/(?P<tecket_id>\d+)$#',
        'model' => 'Tenant_Views_Invoices',
        'method' => 'get',
        'http-method' => 'GET',
        'precond' => array(
            'Pluf_Precondition::ownerRequired'
        )
    ),
    array(
        'regex' => '#^/current/invoice/(?P<tecket_id>\d+)$#',
        'model' => 'Tenant_Views_Invoices',
        'method' => 'update',
        'http-method' => 'POST',
        'precond' => array(
            'Pluf_Precondition::ownerRequired'
        )
    ),
    array(
        'regex' => '#^/current/invoice/(?P<tecket_id>\d+)$#',
        'model' => 'Tenant_Views_Invoices',
        'method' => 'delete',
        'http-method' => 'DELETE',
        'precond' => array(
            'Pluf_Precondition::ownerRequired'
        )
    )
);
