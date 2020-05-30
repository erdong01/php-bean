<?php


namespace Marstm\Test\Collapse;

use Marstm\ArrayList;

require "../../vendor/autoload.php";
$list = ArrayList::new([[1, 2, 3], [4, 5, 6], [7, 8, 9]]);
$list->collapse();
var_dump($list->toArr());