<?php
return array(
    // ************************************************************* Schema
    array(
        'regex' => '#^/settings/schema$#',
        'model' => 'Pluf_Views',
        'method' => 'getSchema',
        'http-method' => 'GET',
        'params' => array(
            'model' => 'Tenant_Setting'
        )
    ),
    // ********************************************************************** Setting
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