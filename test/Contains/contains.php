<?php

namespace Marstm\Test\Contains;

use Marstm\ArrayList;

require "../../vendor/autoload.php";

$res = ArrayList::new([
    ['product' => 'Desk', 'price' => 200],
    ['product' => 'Chair', 'price' => 100],
]);
$b = $res->contains('product', 'Bookcase');
var_dump($b);

$res = ArrayList::new([ 5]);
$b = $res->contains(function ($value, $key) {
    return $value > 5;
});
var_dump($b);