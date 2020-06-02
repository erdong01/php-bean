<?php

namespace Marstm\Test\Map;

use Marstm\ArrayList;

require "../../vendor/autoload.php";
$ArrayList = ArrayList::new([1 => [121 => [131, 132]], 2=>[221=>[231,232]]]);
$ArrayList->map(function ($item, $key) {
    var_dump($item);
//    return $item * 2;
});

//var_dump($ArrayList->toArr());