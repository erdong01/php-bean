<?php

namespace Marstm\Test\GroupBy;

use Marstm\ArrayList;

require "../../vendor/autoload.php";
$list = ArrayList::new([10 => ['user' => 1000, 'skill' => 100, 'roles' => ['Role_1', 'Role_3']],
    20 => ['user' => 2000, 'skill' => 100, 'roles' => ['Role_1', 'Role_2']],
    30 => ['user' => 3000, 'skill' => 200, 'roles' => ['Role_1']],
    40 => ['user' => 4000, 'skill' => 200, 'roles' => ['Role_2']],
    50 => ['user' => 4000, 'skill' => 200, 'roles' => ['Role_2']],
]);
//$list->groupBy([
//    'skill',
//    function ($item) {
//        return $item['roles'];
//    },
//], $preserveKeys = true);

$res = $list->groupBy(['skill',
    function ($item) {
        return $item['roles'];
    },
    'user'
], true);
var_dump($res->toArr());

