<?php

// -------------------------------------------------------------------------
// Database Configurations
// -------------------------------------------------------------------------
$cfg = include 'sqlite.conf.php';

$cfg['test'] = true;
$cfg['debug'] = true;
$cfg['timezone'] = 'Europe/Berlin';
$cfg['installed_apps'] = array(
    'Pluf',
    'User',
    'Tenant',
    'Monitor'
);
$cfg['mimetype'] = 'text/html';
$cfg['app_base'] = '/testapp';
$cfg['url_format'] = 'simple';
$cfg['tmp_folder'] = '/tmp';
$cfg['upload_path'] = '/tmp';
$cfg['middleware_classes'] = array(
    'Tenant_Middleware_ResourceAccess',
    
    'Pluf_Middleware_Session',
    'User_Middleware_Session',
    
     // find tenant
     // TODO: replace with tenant midleware
     'Pluf_Middleware_TenantEmpty',
     'Pluf_Middleware_TenantFromHeader',
     'Pluf_Middleware_TenantFromDomain',
     'Pluf_Middleware_TenantFromSubDomain', // It should be used only in multitenant state
     'Pluf_Middleware_TenantFromConfig',
);
$cfg['secret_key'] = '5a8d7e0f2aad8bdab8f6eef725412850';

// -------------------------------------------------------------------------
// Template manager and compiler
// -------------------------------------------------------------------------
$cfg['templates_folder'] = array(
    dirname(__FILE__) . '/../templates'
);
$cfg['template_tags'] = array(
    'setting' => 'Tenant_Template_Tag_Setting'
);
$cfg['template_modifiers'] = array();

// -------------------------------------------------------------------------
// Logger
// -------------------------------------------------------------------------
$cfg['log_level'] = 'error';
$cfg['log_delayed'] = false;
$cfg['log_formater'] = '\Pluf\LoggerFormatter\Plain';
$cfg['log_appender'] = '\Pluf\LoggerAppender\Console';


// -------------------------------------------------------------------------
// Tenants
// -------------------------------------------------------------------------

// multitenant

// -------------------------------------------------------------------------
// user
// -------------------------------------------------------------------------

$cfg['user_account_auto_activate'] = true;
$cfg['user_avatar_default'] = __DIR__ . '/avatar.svg';

return $cfg;
