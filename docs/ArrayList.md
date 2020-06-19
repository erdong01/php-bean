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