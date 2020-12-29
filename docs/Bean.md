可以做强类型语言功能

在使用类里面直接引入Marstm\Bean

```php
namespace Marstm\Test;

use Marstm\Bean;

class TestJBean
{
    use Bean;
}
```

### 功能方法:

#### new

实例对象

```php
$userBean = UserBean::new();
```

#### ArrayList

列表对象

```php
$userBean = UserBean::ArrayList();
```

#### bind

属性绑定数据

```php
$userBean = UserBean::bind(["user_id" => 12, "user_name" => "new"]);

```

#### setField

设置映射属性字段名，在也不用担心字段名写错，频繁去数据库查询表下有什么字段。

```php
# user. 表前缀，不设置为空
$userBean = UserBean::new()->setField("user.");

//示例一
\DB::table("user")->select($userBean->toArray())->get();

//示例二
\DB::table("user")->select($userBean->getUserName())->where($userBean->getUserId(),"10086")->get();
```

#### toArray

输出数组

```php
$userArr = UserBean::new()->toArray();
```

### phpStorm 编辑器使用

生成get和set：右击class类名 -> 选择Generate -> Getters and Setters -> 选择class 属性 -> ok 就可以生产了

快捷键 alt + insert -> Getters and Setters -> 选择class属性 -> ok

```php
use Marstm\Bean;

class UserBean
{
    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     */
    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return string
     */
    public function getUserName(): string
    {
        return $this->user_name;
    }

    /**
     * @param string $user_name
     */
    public function setUserName(string $user_name): void
    {
        $this->user_name = $user_name;
    }
    use Bean;

    /**
     * 用户id
     * @var int #整型
     */
    private $user_id;
    /**
     * 用户名
     * @var string #字符串类型
     */
    private $user_name;

}
```

### 简单代替数组

```php

$userBean = UserBean::new();
$userBean->setUserName("teset");
$userBean->setUserId(111);
\DB::table("user")->insert($userBean->toArr());

```

### 定义一个构造方法约束初始化赋值

```php

use Marstm\Bean;

class UserBean 
{
    public function __construct($user_id, $user_name){
        $this->setUserId($user_id);
    }
    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    /**
     * @param int|null $user_id
     */
    public function setUserId(?int $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return string|null
     */
    public function getUserName(): ?string
    {
        return $this->user_name;
    }

    /**
     * @param string|null $user_name
     */
    public function setUserName(?string $user_name): void
    {
        $this->user_name = $user_name;
    }

    use Bean;
    /**
     * 用户id
     * @var int|null #整型
     */
    private $user_id;

    /**
     * 用户名
     * @var string|null #字符串类型
     */
    private $user_name;
}

$userBean = UserBean::new(0, "testName");

```