<?php

namespace Marstm\Test\Duplicates;
require "../../vendor/autoload.php";
$collection = collect(['a', 'b', 'a', 'c', 'b']);

$res = $collection->duplicatesStrict();
//dump($res->all());

$arrayList = arrayList(['a', 'b', 'a', 'c', 'b']);

$res = $arrayList->duplicates();
dump($res->all());


$arrayList = arrayList([
    ['email' => 'abigail@example.com', 'position' => 'Developer'],
    ['email' => 'james@example.com', 'position' => 'Designer'],
    ['email' => 'victoria@example.com', 'position' => 'Developer'],
]);
$res = $arrayList->duplicates('position');
dump($res->all());
