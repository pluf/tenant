<?php
$paths = array(
    'urls/tenant.php',
    'urls/configuration.php',
    'urls/setting.php',
    'urls/bank.php',
    'urls/invoice.php',
    'urls/resource.php',
    'urls/ticket.php',
    'urls/spa.php',
    'urls/spa-repository.php'
);

$api = array();

foreach ($paths as $path){
    $myApi = include $path;
    $api = array_merge($api, $myApi);
}

return $api;

