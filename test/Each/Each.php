<?php


namespace Marstm\Test\Each;

require "../../vendor/autoload.php";
$collection = collect([
    ['email' => 'abigail@example.com', 'position' => 'Developer'],
    ['email' => 'james@example.com', 'position' => 'Designer'],
    ['email' => 'victoria@example.com', 'position' => 'Developer'],
]);

$collection->each(function ($item, $key) {
});

$arrayList = arrayList([
    ['email' => 'abigail@example.com', 'position' => 'Developer'],
    ['email' => 'james@example.com', 'position' => 'Designer'],
    ['email' => 'victoria@example.com', 'position' => 'Developer'],
]);

$arrayList->each(function ($item, $key) {
});

$res =collect([1, 2, 3, 4])->every(function ($value, $key) {
    return $value > 2;
});

$res =arrayList([1, 2, 3, 4])->every(function ($value, $key) {
    return $value > 2;
});
var_dump($res);
