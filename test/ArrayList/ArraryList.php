<?php

namespace Marstm\Test\ArrayList;

require "../../vendor/autoload.php";

$average = arrayList([['foo' => 10], ['foo' => 10], ['foo' => 20], ['foo' => 40]])->avg('foo');
var_dump($average);
//int(20)
$average = arrayList([1, 1, 2, 4])->avg();
var_dump($average);
//int(2)
