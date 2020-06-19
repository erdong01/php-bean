<?php

namespace Marstm\Test\Contains;

use Marstm\ArrayList;

require "../../vendor/autoload.php";

$arrList = ArrayList::new([1, 2, 3, 4, 5]);
$arrList->diff([2, 4, 6, 8]);

$res = $arrList->all();
var_dump($res);


$collection = collect([
    'color' => 'orange',
    'type' => 'fruit',
    'remain' => 6
]);

$diff = $collection->diffAssoc([
    'color' => 'yellow',
    'type' => 'fruit',
    'remain' => 3,
    'used' => 6,
]);

$res = $diff->all();
dd($res);

$arrList = ArrayList::new([
    'color' => 'yellow',
    'type' => 'fruit',
    'remain' => 3,
    'used' => 6,
]);
$arrList->diffAssoc([
    'color' => 'yellow',
    'type' => 'fruit',
    'remain' => 3,
    'used' => 6,
]);

$res = $arrList->all();
var_dump($res);


$collection = collect([
    'one' => 10,
    'two' => 20,
    'three' => 30,
    'four' => 40,
    'five' => 50,
]);

$diff = $collection->diffKeys([
    'two' => 2,
    'four' => 4,
    'six' => 6,
    'eight' => 8,
]);

$diff->all();

