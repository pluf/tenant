<?php
require 'config.common.php';

$cfg['multitenant'] = false;
$cfg['multitenant_default'] = array(
        'level' => 10,
        'title' => 'Tenant title',
        'description' => 'Default tenant in single mode',
        'domain' => 'mydomain.com',
        'subdomain' => 'www',
        'validate' => 1
);

return $cfg;

