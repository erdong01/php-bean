<?php

namespace Marstm\Test\Contains;

use Marstm\ArrayList;

require "../../vendor/autoload.php";

$arrayList = arrayList(['name' => 'Desk', 'price' => 100]);
var_dump($arrayList->contains('Desk'));
var_dump($arrayList->contains('New York'));

$arrayList = arrayList([
    ['product' => 'Desk', 'price' => 200],
    ['product' => 'Chair', 'price' => 100],
]);
var_dump($arrayList->contains('product', 'Bookcase'));


$res = ArrayList::new([1, 2, 3, 4, 5]);
var_dump($res->contains(function ($value, $key) {
    return $value > 5;
}));