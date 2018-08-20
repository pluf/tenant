<?php
$paths = array(
    'urls/tenant-legacy.php'
);

$shopApi = array();

foreach ($paths as $path){
    $myApi = include $path;
    $shopApi = array_merge($shopApi, $myApi);
}

return $shopApi;

