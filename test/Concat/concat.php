<?php


namespace Marstm\Test\Concat;


use Marstm\ArrayList;

require "../../vendor/autoload.php";


$arrList = ArrayList::new(['John Doe']);
$arrList->concat(['Jane Doe'])->concat(['name' => 'Johnny Doe']);

var_dump($arrList->all());