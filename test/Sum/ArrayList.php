<?php

namespace Marstm\Test\Sum;
require "../../vendor/autoload.php";

use Marstm\ArrayList;

$res = ArrayList::new([1, 2, 3, 4, 5]);
var_dump($res->sum());


$res = ArrayList::new([
    ['name' => 'JavaScript: The Good Parts', 'pages' => 176],
    ['name' => 'JavaScript: The Definitive Guide', 'pages' => 1096]
]);
var_dump($res->sum('pages'));


$res = ArrayList::new([
    ['name' => 'Chair', 'colors' => ['Black']],
    ['name' => 'Desk', 'colors' => ['Black', 'Mahogany']],
    ['name' => 'Bookcase', 'colors' => ['Red', 'Beige', 'Brown']],
]);
var_dump($res->sum(function ($product) {
    return count($product['colors']);
}));

$res = ArrayList::new([
]);
var_dump($res->sum());

$res = ArrayList::new([1, 2, 3, 4, 5]);
var_dump($res->avg());
