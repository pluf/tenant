<?php
return array( // **************************************************************** Bank Backend
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
            'sql' => 'owner_class="tenant-invoice"'
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
    )
);

