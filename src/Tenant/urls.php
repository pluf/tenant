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
    array( // Get
        'regex' => '#^/tenant/current$#',
        'model' => 'Tenant_Views',
        'method' => 'current',
        'http-method' => 'GET',
        'precond' => array()
    ),
    array( // Update
        'regex' => '#^/tenant/current$#',
        'model' => 'Tenant_Views',
        'method' => 'update',
        'http-method' => 'POST',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    array( // Delete
        'regex' => '#^/tenant/current$#',
        'model' => 'Tenant_Views',
        'method' => 'delete',
        'http-method' => 'DELETE',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    // **************************************************************** Ticket
    array( // Find
        'regex' => '#^/ticket/find$#',
        'model' => 'Pluf_Views',
        'method' => 'findObject',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
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
    array( // Create
        'regex' => '#^/ticket/new$#',
        'model' => 'Pluf_Views',
        'method' => 'createObject',
        'http-method' => 'POST',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'model' => 'Tenant_Ticket'
        )
    ),
    array( // Get info
        'regex' => '#^/ticket/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'getObject',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'model' => 'Tenant_Ticket'
        )
    ),
    array( // Update
        'regex' => '#^/ticket/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'updateObject',
        'http-method' => 'POST',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'model' => 'Tenant_Ticket'
        )
    ),
    array( // Delete
        'regex' => '#^/ticket/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'deleteObject',
        'http-method' => 'DELETE',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'model' => 'Tenant_Ticket'
        )
    ),
    // **************************************************************** Comments of ticket
    array( // Find
        'regex' => '#^/ticket/(?P<parentId>\d+)/comment/find$#',
        'model' => 'Pluf_Views',
        'method' => 'findManyToOne',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'model' => 'Tenant_Comment',
            'parent' => 'Tenant_Ticket',
            'parentKey' => 'ticket',
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
    array( // Create
        'regex' => '#^/ticket/(?P<parentId>\d+)/comment/new$#',
        'model' => 'Tenant_Views_Ticket',
        'method' => 'createManyToOne',
        'http-method' => 'POST',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'model' => 'Tenant_Comment',
            'parent' => 'Tenant_Ticket',
            'parentKey' => 'ticket'
        )
    ),
    array( // Get
        'regex' => '#^/ticket/(?P<parentId>\d+)/comment/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'getManyToOne',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'model' => 'Tenant_Comment',
            'parent' => 'Tenant_Ticket',
            'parentKey' => 'ticket'
        )
    ),
    array( // Update
        'regex' => '#^/ticket/(?P<parentId>\d+)/comment/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'updateManyToOne',
        'http-method' => 'POST',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'model' => 'Tenant_Comment',
            'parent' => 'Tenant_Ticket',
            'parentKey' => 'ticket'
        )
    ),
    array( // Delete
        'regex' => '#^/ticket/(?P<parentId>\d+)/comment/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'deleteManyToOne',
        'http-method' => 'DELETE',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'model' => 'Tenant_Comment',
            'parent' => 'Tenant_Ticket',
            'parentKey' => 'ticket'
        )
    ),
    
    // **************************************************************** Invoices
    array( // Find
        'regex' => '#^/invoice/find$#',
        'model' => 'Pluf_Views',
        'method' => 'findObject',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'model' => 'Tenant_Invoice',
            'listFilters' => array(
                'id',
                'status'
            ),
            'listDisplay' => array(),
            'searchFields' => array(
                'title',
                'description'
            ),
            'sortFields' => array(
                'id',
                'status',
                'amount',
                'due_dtiem',
                'modif_dtime',
                'creation_dtime'
            ),
            'sortOrder' => array(
                'id',
                'DESC'
            )
        )
    ),
    array( // Get
        'regex' => '#^/invoice/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'getObject',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'model' => 'Tenant_Invoice'
        )
    ),
    array( // pay invoice for tenant
        'regex' => '#^/invoice/(?P<modelId>\d+)/pay$#',
        'model' => 'Tenant_Views_Invoice',
        'method' => 'payment',
        'http-method' => 'POST',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    array( // check payment state
        'regex' => '#^/invoice/(?P<modelId>\d+)/state$#',
        'model' => 'Tenant_Views_Invoice',
        'method' => 'checkPaymentState',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    // **************************************************************** Bank Backend
    array( // Find
        'regex' => '#^/backend/find$#',
        'model' => 'Pluf_Views',
        'method' => 'findObject',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::loginRequired'
        ),
        'params' => array(
            'model' => 'Tenant_BankBackend',
            'model_view' => 'global',
            'listFilters' => array(
                'id',
                'title',
                'home',
                'engine'
            ),
            'listDisplay' => array(),
            'searchFields' => array(
                'title',
                'description'
            ),
            'sortFields' => array(
                'id',
                'title',
                'creation_dtime'
            ),
            'sortOrder' => array(
                'creation_dtime',
                'DESC'
            )
        )
    ),
    array( // Get
        'regex' => '#^/backend/(?P<modelId>\d+)$#',
        'model' => 'Tenant_Views_BankBackend',
        'method' => 'get',
        'http-method' => 'GET',
        'precond' => array(),
        'params' => array(
            'model' => 'Tenant_BankBackend'
        )
    ),
    // **************************************************************** Receipt
    array( // Find
        'regex' => '#^/receipt/find$#',
        'model' => 'Pluf_Views',
        'method' => 'findObject',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'model' => 'Bank_Receipt',
            'sql' => new Pluf_SQL('owner_class="tenant-invoice"'),
            'listFilters' => array(
                'id',
                'title',
                'secure_id',
                'backend'
            ),
            'listDisplay' => array(),
            'searchFields' => array(
                'title',
                'description'
            ),
            'sortFields' => array(
                'id',
                'title',
                'creation_dtime'
            ),
            'sortOrder' => array(
                'creation_dtime',
                'DESC'
            )
        )
    ),
    // array( // Create
    // 'regex' => '#^/receipt/new$#',
    // 'model' => 'Bank_Views_Receipt',
    // 'method' => 'create',
    // 'http-method' => array(
    // 'POST'
    // )
    // ),
    array( // Get
        'regex' => '#^/receipt/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'getObject',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'model' => 'Bank_Receipt'
        )
    ),
    array( // Get (by secure id)
        'regex' => '#^/receipt/(?P<secure_id>.+)$#',
        'model' => 'Tenant_Views_Receipt',
        'method' => 'getBySecureId',
        'http-method' => array(
            'GET'
        )
    )
);
