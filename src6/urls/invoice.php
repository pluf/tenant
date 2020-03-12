<?php
return array( // **************************************************************** Invoices
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
    )
);