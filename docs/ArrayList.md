 ### ArrayList是Bean数据扩展处理

#### 加入 Bean数据

```php
$userBean = UserBean::new(0, "testName");
arrayList($userBean);

//[["user_id"=>0,"user_name"=>"test"]]
```
#### all()
all 方法返回代表集合的底层数组：
```php
arrayList([1,2,3])->all();

// [1,2,3]
```
#### avg()
average()

avg 方法的别名。

avg()

avg 方法返回指定键的 平均值 ：
```php
$average = arrayList([['foo' => 10], ['foo' => 10], ['foo' => 20], ['foo' => 40]])->avg('foo');
var_dump($average);
//int(20)
$average = arrayList([1, 1, 2, 4])->avg();
var_dump($average);
//int(2)
```
#### chunk()
chunk 方法将集合拆成多个指定大小的小集合：
```php
$collection = collect([1, 2, 3, 4, 5, 6, 7]);
$chunks = $collection->chunk(4);
var_dump($chunks->all());

// [[1, 2, 3, 4], [5, 6, 7]]
```
#### collapse()
collapse 方法将多个数组的集合合并成一个数组的集合：
```php
$list = arrayList([[1, 2, 3], [4, 5, 6], [7, 8, 9]]);
$list->collapse();
var_dump($list->toArr());

// [1, 2, 3, 4, 5, 6, 7, 8, 9]
```
#### contains()
contains 方法判断集合是否包含给定的项目：
```php
$arrayList = arrayList(['name' => 'Desk', 'price' => 100]);
var_dump($arrayList->contains('Desk'));

// true

var_dump($arrayList->contains('New York'));

//false
```
你也可以用 contains 方法匹配一对键 / 值，即判断给定的配对是否存在于集合中：
```php
$arrayList = arrayList([
    ['product' => 'Desk', 'price' => 200],
    ['product' => 'Chair', 'price' => 100],
]);
var_dump($arrayList->contains('product', 'Bookcase'));

//false
```
最后，你也可以传递一个回调到 contains 方法来执行自己的真实测试：
```php
$res = ArrayList::new([ 1, 2, 3, 4, 5]);
var_dump($res->contains(function ($value, $key) {
    return $value > 5;
}));

//false
```
#### diff()
diff  方法将集合与其它集合或纯 PHP 数组进行值的比较，然后返回原集合中存在而给定集合中不存在的值：
```php
$arrList = \arrayList([1, 2, 3, 4, 5]);
$arrList->diff([2, 4, 6, 8]);

$res = $arrList->all();
var_dump($res);

// [1, 3, 5]
```
#### diffAssoc()
diffAssoc 该方法与另外一个集合或基于它的键和值的 PHP 数组进行比较。这个方法会返回原集合不存在于给定集合中的键值对 ：
```php
$collection = collect([
    'color' => 'orange',
    'type' => 'fruit',
    'remain' => 6
]);
$diff = $collection->diffAssoc([
    'color' => 'yellow',
    'type' => 'fruit',
    'remain' => 3,
    'used' => 6,
]);
$res = $diff->all();
var_dump($res);
// ['color' => 'orange', 'remain' => 6]
```
#### diffKeys()
diffKeys 方法与另外一个集合或 PHP 数组的「键」进行比较，然后返回原集合中存在而给定的集合中不存在「键」所对应的键值对：
```php
$collection = arrayList([
    'one' => 10,
    'two' => 20,
    'three' => 30,
    'four' => 40,
    'five' => 50,
]);
$diff = $collection->diffKeys([
    'two' => 2,
    'four' => 4,
    'six' => 6,
    'eight' => 8,
]);
var_dump($diff->all());

// ['one' => 10, 'three' => 30, 'five' => 50]
```
#### column()
column 跟php array_column()一样功能；
```php
$arrayList = arrayList([
    ['product_id' => 'prod-100', 'name' => 'Desk'],
    ['product_id' => 'prod-200', 'name' => 'Chair'],
]);
$res = $arrayList->column('name', 'product_id');
var_dump($res->toArr());

// [["prod-100"]=> "Desk", ["prod-200"]=> "Chair"]
```
#### keyBy()
keyBy 方法以给定的键作为集合的键。如果多个项目具有相同的键，则只有最后一个项目会显示在新集合中：
```php
$arrayList = arrayList([
    ['product_id' => 'prod-100', 'name' => 'Desk'],
    ['product_id' => 'prod-200', 'name' => 'Chair'],
]);
$keyed = $collection->keyBy('product_id');
var_dump($res->all());
/*
    [
        'prod-100' => ['product_id' => 'prod-100', 'name' => 'Desk'],
        'prod-200' => ['product_id' => 'prod-200', 'name' => 'Chair'],
    ]
*/
```

你也可以传入一个回调方法，回调返回的值会作为该集合的键：
```php
$arrayList = arrayList([
    ['product_id' => 'prod-100', 'name' => 'Desk'],
    ['product_id' => 'prod-200', 'name' => 'Chair'],
]);
$arr = $arrayList->keyBy(function ($item) {
    return strtoupper($item['product_id']);
});
var_dump($arr->all());

/*
    [
        'PROD-100' => ['product_id' => 'prod-100', 'name' => 'Desk'],
        'PROD-200' => ['product_id' => 'prod-200', 'name' => 'Chair'],
    ]
*/
```
#### groupBy()
groupBy 方法根据给定的键对集合内的项目进行分组：
```php
$arrayList = arrayList([
    ['account_id' => 'account-x10', 'product' => 'Chair'],
    ['account_id' => 'account-x10', 'product' => 'Bookcase'],
    ['account_id' => 'account-x11', 'product' => 'Desk'],
]);

$grouped = $arrayList->groupBy('account_id');
var_dump($grouped->all());
/*
    [
        'account-x10' => [
            ['account_id' => 'account-x10', 'product' => 'Chair'],
            ['account_id' => 'account-x10', 'product' => 'Bookcase'],
        ],
        'account-x11' => [
            ['account_id' => 'account-x11', 'product' => 'Desk'],
        ],
    ]
*/
```
除了传入一个字符串的「键」，你还可以传入一个回调。该回调应该返回你希望用来分组的键的值。
```php
$grouped = $arrayList->groupBy(function ($item) {
    return substr($item['account_id'], -3);
});
var_dump($grouped->all());
/*
    [
        'x10' => [
            ['account_id' => 'account-x10', 'product' => 'Chair'],
            ['account_id' => 'account-x10', 'product' => 'Bookcase'],
        ],
        'x11' => [
            ['account_id' => 'account-x11', 'product' => 'Desk'],
        ],
    ]
*/
```
可以传递一个数组用于多重分组标准。每一个数组元素将对应多维数组内的相应级别：
```php
$list = arrayList([10 => ['user' => 1000, 'skill' => 100, 'roles' => ['Role_1', 'Role_3']],
    20 => ['user' => 2000, 'skill' => 100, 'roles' => ['Role_1', 'Role_2']],
    30 => ['user' => 3000, 'skill' => 200, 'roles' => ['Role_1']],
    40 => ['user' => 4000, 'skill' => 200, 'roles' => ['Role_2']],
    50 => ['user' => 4000, 'skill' => 200, 'roles' => ['Role_2']],
]);
$res = $list->groupBy(['skill',
    function ($item) {
        return $item['roles'];
    }
], true);
var_dump($res->toArr());
/*
[
    1 => [
        'Role_1' => [
            10 => ['user' => 1, 'skill' => 1, 'roles' => ['Role_1', 'Role_3']],
            20 => ['user' => 2, 'skill' => 1, 'roles' => ['Role_1', 'Role_2']],
        ],
        'Role_2' => [
            20 => ['user' => 2, 'skill' => 1, 'roles' => ['Role_1', 'Role_2']],
        ],
        'Role_3' => [
            10 => ['user' => 1, 'skill' => 1, 'roles' => ['Role_1', 'Role_3']],
        ],
    ],
    2 => [
        'Role_1' => [
            30 => ['user' => 3, 'skill' => 2, 'roles' => ['Role_1']],
        ],
        'Role_2' => [
            40 => ['user' => 4, 'skill' => 2, 'roles' => ['Role_2']],
        ],
    ],
];
*/
```