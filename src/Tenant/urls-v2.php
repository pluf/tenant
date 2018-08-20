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
//     array( // Get
//         'regex' => '#^/$#',
//         'model' => 'Tenant_Views',
//         'method' => 'current',
//         'http-method' => 'GET',
//         'precond' => array()
//     ),
//     array( // Update
//         'regex' => '#^/$#',
//         'model' => 'Tenant_Views',
//         'method' => 'update',
//         'http-method' => 'POST',
//         'precond' => array(
//             'User_Precondition::ownerRequired'
//         )
//     ),
//     array( // Delete
//         'regex' => '#^/$#',
//         'model' => 'Tenant_Views',
//         'method' => 'delete',
//         'http-method' => 'DELETE',
//         'precond' => array(
//             'User_Precondition::ownerRequired'
//         )
//     ),
    // **************************************************************** Ticket
    array( // Create
        'regex' => '#^/tickets$#',
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
    array( // Read (list)
        'regex' => '#^/tickets$#',
        'model' => 'Pluf_Views',
        'method' => 'findObject',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'model' => 'Tenant_Ticket'
        )
    ),
    array( // Read
        'regex' => '#^/tickets/(?P<modelId>\d+)$#',
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
        'regex' => '#^/tickets/(?P<modelId>\d+)$#',
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
        'regex' => '#^/tickets/(?P<modelId>\d+)$#',
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
    array( // Create
        'regex' => '#^/tickets/(?P<parentId>\d+)/comments$#',
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
    array( // Read (list)
        'regex' => '#^/tickets/(?P<parentId>\d+)/comments$#',
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
    array( // Read
        'regex' => '#^/tickets/(?P<parentId>\d+)/comments/(?P<modelId>\d+)$#',
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
        'regex' => '#^/tickets/(?P<parentId>\d+)/comments/(?P<modelId>\d+)$#',
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
        'regex' => '#^/tickets/(?P<parentId>\d+)/comments/(?P<modelId>\d+)$#',
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
    array( // Read (list)
        'regex' => '#^/invoices$#',
        'model' => 'Pluf_Views',
        'method' => 'findObject',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'model' => 'Tenant_Invoice'
        )
    ),
    array( // Read
        'regex' => '#^/invoices/(?P<modelId>\d+)$#',
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
    array( // Create receipt for invoice (pay invoice)
        'regex' => '#^/invoices/(?P<modelId>\d+)/receipts$#',
        'model' => 'Tenant_Views_Invoice',
        'method' => 'payment',
        'http-method' => 'POST',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    array( // check payment state
        'regex' => '#^/invoices/(?P<modelId>\d+)/receipts$#',
        'model' => 'Tenant_Views_Invoice',
        'method' => 'checkPaymentState',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    // **************************************************************** Bank Backend
    array( // Read (list)
        'regex' => '#^/bank-backends$#',
        'model' => 'Tenant_Views_BankBackend',
        'method' => 'find',
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
    array( // Read
        'regex' => '#^/bank-backends/(?P<modelId>\d+)$#',
        'model' => 'Tenant_Views_BankBackend',
        'method' => 'get',
        'http-method' => 'GET',
        'precond' => array(),
        'params' => array(
            'model' => 'Tenant_BankBackend'
        )
    ),
    // **************************************************************** Receipt
    array( // Read (list)
        'regex' => '#^/receipts$#',
        'model' => 'Pluf_Views',
        'method' => 'findObject',
        'http-method' => 'GET',
        'precond' => array(
            'User_Precondition::ownerRequired'
        ),
        'params' => array(
            'model' => 'Bank_Receipt',
            'sql' => new Pluf_SQL('owner_class="tenant-invoice"')
        )
    ),
    array( // Read
        'regex' => '#^/receipts/(?P<modelId>\d+)$#',
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
    array( // Read (by secure id)
        'regex' => '#^/receipts/(?P<secure_id>.+)$#',
        'model' => 'Tenant_Views_Receipt',
        'method' => 'getBySecureId',
        'http-method' => array(
            'GET'
        )
    ),
    //********************************************************************** Setting
    
    // TODO: maso, 2017: some attributes are not readable by users
    array( // Read (list)
        'regex' => '#^/settings$#',
        'model' => 'Tenant_Views_Setting',
        'method' => 'findObject',
        'http-method' => 'GET',
        'precond' => array(),
        'params' => array(
            'model' => 'Tenant_Setting'
        )
    ),
    array( // Read
        'regex' => '#^/settings/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'getObject',
        'http-method' => 'GET',
        'params' => array(
            'model' => 'Tenant_Setting'
        )
    ),
    array( // Read (by key)
        'regex' => '#^/settings/(?P<key>[^/]+)$#',
        'model' => 'Tenant_Views_Setting',
        'method' => 'get',
        'http-method' => 'GET'
    ),
    array( // Delete/Clear
        'regex' => '#^/settings/(?P<modelId>\d+)$#',
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
    array( // Delete/Clear (by key)
        'regex' => '#^/settings/(?P<key>[^/]+)$#',
        'model' => 'Tenant_Views_Setting',
        'method' => 'deleteByKey',
        'http-method' => 'DELETE',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    array( // Create
        'regex' => '#^/settings$#',
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
    array( // Update
        'regex' => '#^/settings/(?P<modelId>\d+)$#',
        'model' => 'Pluf_Views',
        'method' => 'updateObject',
        'http-method' => 'POST',
        'params' => array(
            'model' => 'Tenant_Setting'
        ),
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    ),
    array( // Update (by key)
        'regex' => '#^/settings/(?P<key>[^/]+)$#',
        'model' => 'Tenant_Views_Setting',
        'method' => 'updateByKey',
        'http-method' => 'POST',
        'precond' => array(
            'User_Precondition::ownerRequired'
        )
    )
);
