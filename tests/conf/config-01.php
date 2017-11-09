<?php

return array(
    'general_domain' => 'pluf.ir',
    'general_admin_email' => array(
        'info@pluf.ir'
    ),
    'general_from_email' => 'info@pluf.ir',
    'general_new_request_mail_title' => 'Pluf Request',
    
    'installed_apps' => array(
        'Pluf',
        'Tenant',
        'User'
    ),
    'middleware_classes' => array(
        'Pluf_Middleware_Session',
        'Pluf_Middleware_TenantFromDomain',
    ),
    'debug' => true,
    
    'languages' => array(
        'fa',
        'en'
    ),
    'tmp_folder' => dirname(__FILE__) . '/../tmp',
    'template_folders' => array(
        // Templates
    ),
    'template_tags' => array(
        // Tags
    ),
    'upload_path' => dirname(__FILE__) . '/../tmp',
    'upload_max_size' => 52428800,
    'time_zone' => 'Asia/Tehran',
    'encoding' => 'UTF-8',
    
    'secret_key' => '5a8d7e0f2aad8bdab8f6eef725412850',
    'auth_backends' => array(
        'Pluf_Auth_ModelBackend'
    ),
    'pluf_use_rowpermission' => true,
    
    'db_engine' => 'MySQL',
    'db_version' => '5.5.33',
    
    'db_login' => 'root',
    'db_password' => '',
    'db_server' => 'localhost',
    'db_database' => 'test',
    'db_table_prefix' => 'tenant_unit_test_',
    
    'tenant_default' => 'test',
    'multitenant' => true,
    'migrate_allow_web' => true,
);

