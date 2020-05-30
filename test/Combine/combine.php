<?php


namespace Marstm\Test\Combine;


use Marstm\ArrayList;

require "../../vendor/autoload.php";


$list = ArrayList::new(['name', 'age']);
$list->combine(["test" => 1, "test2" => 2]);
var_dump($list->toArr());