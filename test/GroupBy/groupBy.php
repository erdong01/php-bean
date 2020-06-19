<?php

namespace Marstm\Test\GroupBy;

use Marstm\ArrayList;

require "../../vendor/autoload.php";
$list = \arrayList([10 => ['user' => 1000, 'skill' => 100, 'roles' => ['Role_1', 'Role_3']],
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

//$res = $list->groupBy(['skill',
//    function ($item) {
//        return $item['roles'];
//    }
//], true);
//dd($res->toArr());
//
//$arrayList = arrayList([
//    ['account_id' => 'account-x10', 'product' => 'Chair'],
//    ['account_id' => 'account-x10', 'product' => 'Bookcase'],
//    ['account_id' => 'account-x11', 'product' => 'Desk'],
//]);

//$grouped = $arrayList->groupBy('account_id');
//dd($grouped->all());

//$grouped = $arrayList->groupBy(function ($item) {
//    return substr($item['account_id'], -3);
//});
//dd($grouped->all());


$data = arrayList([
    10 => ['user' => 1, 'skill' => 1, 'roles' => ['Role_1', 'Role_3']],
    20 => ['user' => 2, 'skill' => 1, 'roles' => ['Role_1', 'Role_2']],
    30 => ['user' => 3, 'skill' => 2, 'roles' => ['Role_1']],
    40 => ['user' => 4, 'skill' => 2, 'roles' => ['Role_2']],
]);
$result = $data->groupBy([
    'skill',
    function ($item) {
        return $item['roles'];
    },
], $preserveKeys = true);
dd($result->toArr());





