<?php


namespace Marstm\Test\KeyBy;

use Marstm\ArrayList;

require "../../vendor/autoload.php";

$arrayList = ArrayList::new([
    ['product_id' => 'prod-100', 'name' => 'Desk'],
    ['product_id' => 'prod-200', 'name' => 'Chair'],
]);

//$arr = $arrayList->keyBy(function ($item) {
//    return strtoupper($item['product_id']);
//});
//
//var_dump($arr);


$res = $arrayList->column('name', 'product_id',);
var_dump($res->toArr());

