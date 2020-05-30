<?php


namespace Marstm\Test\Chunk;

use Marstm\ArrayList;

require "../../vendor/autoload.php";

$arr = ArrayList::new([1, 2, 3, 4, 5, 6, 7]);
$res = $arr->chunk(4);
var_dump($res->toArr());
