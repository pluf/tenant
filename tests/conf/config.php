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
     '\Pluf\Middleware\Tenant',
    'Tenant_Middleware_ResourceAccess',
    '\Pluf\Middleware\Session',
    'User_Middleware_Session',
);
$cfg['secret_key'] = '5a8d7e0f2aad8bdab8f6eef725412850';

// -------------------------------------------------------------------------
// Template manager and compiler
// -------------------------------------------------------------------------
$cfg['templates_folders'] = array(
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

$cfg['multitenant'] = false;

// -------------------------------------------------------------------------
// user
// -------------------------------------------------------------------------

$cfg['user_account_auto_activate'] = true;
$cfg['user_avatar_default'] = __DIR__ . '/avatar.svg';

return $cfg;
