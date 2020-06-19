<?php

namespace Marstm\Test\Contains;

use Marstm\ArrayList;

require "../../vendor/autoload.php";

//$arrList = \arrayList([1, 2, 3, 4, 5]);
//$arrList->diff([2, 4, 6, 8]);
//
//$res = $arrList->all();
//var_dump($res);


//$arrList = arrayList::new([
//    'color' => 'orange',
//    'type' => 'fruit',
//    'remain' => 6
//]);
//$arrList->diffAssoc([
//    'color' => 'yellow',
//    'type' => 'fruit',
//    'remain' => 3,
//    'used' => 6
//]);
//
//$res = $arrList->all();
//var_dump($res);


$collection = arrayList([
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
var_dump($diff->all());

