<?php
// $cfg = include 'mysql.config.php';
$cfg = include 'sqlite.config.php';



$cfg['test'] = false;
$cfg['timezone'] = 'Europe/Berlin';

// Set the debug variable to true to force the recompilation of all
// the templates each time during development
$cfg['debug'] = true;
$cfg['installed_apps'] = array(
    'Pluf',
    'User',
    'Role',
    'Group',
    'Monitor',
    'Tenant'
);

$cfg['multitenant'] = true;

/*
 * Middlewares
 */
$cfg['middleware_classes'] = array(
    // find tenant
    'Pluf_Middleware_TenantEmpty',
    'Pluf_Middleware_TenantFromHeader',
    'Pluf_Middleware_TenantFromDomain',
    'Pluf_Middleware_TenantFromSubDomain', // It should be used only in multitenant state
    'Pluf_Middleware_TenantFromConfig',
    // Sessions
    'Pluf_Middleware_Session',
    'User_Middleware_Session'
);

$cfg['secret_key'] = '5a8d7e0f2aad8bdab8f6eef725412850';

// Temporary folder where the script is writing the compiled templates,
// cached data and other temporary resources.
// It must be writeable by your webserver instance.
// It is mandatory if you are using the template system.
$cfg['tmp_folder'] = __DIR__ . '/../tmp';
$cfg['upload_path'] =  __DIR__ . '/../storage/tenant';

// The folder in which the templates of the application are located.
$cfg['templates_folder'] = array(
    __DIR__ . '/../templates'
);

/*
 * Template tags
 */
$cfg['template_tags'] = array(
    'config' => 'Pluf_Template_Tag_Mytag',
    'setting' => 'Tenant_Template_Tag_Setting'
);

// Default mimetype of the document your application is sending.
// It can be overwritten for a given response if needed.
$cfg['mimetype'] = 'text/html';


return $cfg;

