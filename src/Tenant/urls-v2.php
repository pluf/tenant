<?php
$paths = array(
    'urls/tenant-v2.php',
    'urls/spa-v2.php',
    'urls/spa-repository-v2.php'
);

$shopApi = array();

foreach ($paths as $path){
    $myApi = include $path;
    $shopApi = array_merge($shopApi, $myApi);
}

return $shopApi;

