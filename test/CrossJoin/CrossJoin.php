<?php


namespace Marstm\Test\CrossJoin;

require "../../vendor/autoload.php";

$collection = collect([1, 2]);

$matrix = $collection->crossJoin(['a', 'b']);

var_dump($matrix->all());

$arrayList = arrayList([1, 2]);

$matrix = $arrayList->crossJoin(['a', 'b']);
var_dump($matrix->all());

$arrayList = arrayList([1, 2]);
$matrix = $arrayList->crossJoin(['a', 'b'], ['I', 'II']);
var_dump($matrix->all());