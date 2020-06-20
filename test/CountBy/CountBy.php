<?php


namespace Marstm\Test\CountBy;

require "../../vendor/autoload.php";


$collection = collect([1, 2, 2, 2, 3]);
$counted = $collection->countBy();
dump($counted->all());

$arrayList = arrayList([1, 2, 2, 2, 3])->countBy();
dump($arrayList->all());

$collection = collect(['alice@gmail.com', 'bob@yahoo.com', 'carlos@gmail.com']);

$counted = $collection->countBy(function ($email) {
    return substr(strrchr($email, "@"), 1);
});

dump($counted->all());

$arrayList = arrayList(['alice@gmail.com', 'bob@yahoo.com', 'carlos@gmail.com']);
$arrayList = $arrayList->countBy(function ($email) {
    return substr(strrchr($email, "@"), 1);
});
var_dump($arrayList->all());
