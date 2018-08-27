<?php
$paths = array(
    'urls-v2/tenant.php',
    'urls-v2/configuration.php',
    'urls-v2/setting.php',
    'urls-v2/bank.php',
    'urls-v2/invoice.php',
    'urls-v2/ticket.php',
    'urls-v2/spa.php',
    'urls-v2/spa-repository.php'
);

$shopApi = array();

foreach ($paths as $path){
    $myApi = include $path;
    $shopApi = array_merge($shopApi, $myApi);
}

return $shopApi;

