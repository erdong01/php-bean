<?php

namespace Marstm\Test;

require "../vendor/autoload.php";

use Marstm\Support\Bean;
use Marstm\ArrayList;
use Marstm\Support\Arr;

class Base
{
    /**
     * @return string
     */
    public function getB(): string
    {
        return $this->b;
    }

    /**
     * @param string $b
     */
    public function setB(string $b): void
    {
        $this->b = $b;
    }


    private $ad = '11';
    protected $b = 'bb';

    public $c = 'cc';

}

/**
 * Class UserBean
 * @package Marstm\Test
 */
class UserBean extends Base
{
    public function __construct($user_id, $user_name)
    {
        $this->setUserId($user_id);
        $this->setUserName($user_name);
    }

    /**
     * @return bool|null
     */
    public function getIsRegister(): ?bool
    {
        return $this->is_register;
    }

    /**
     * @param bool|null $is_register
     */
    public function setIsRegister(?bool $is_register): void
    {
        $this->is_register = $is_register;
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

    /**
     * @var bool|null
     */
    private $is_register;

}

$userBean = UserBean::new(0, "");
//$userBean->setUserName("teset");
//$userBean->setUserId(111);
$userBean->setIsRegister(false);

//$userBean1 = UserBean::new()->setField("user.");
//var_dump($userBean1->toArr());
//var_dump($userBean->toArr());
//
//$userBean2 = UserBean::bind(["user_id" => 12, "user_name" => "new"]);
//var_dump($userBean2->toArr());

$beanList = ArrayList::new();

$beanList->add($userBean);

$beanList2 = UserBean::new(22, "test");
//$beanList->add($beanList2);
//$beanList->add(["bb" => "aaa"]);
//$beanList->add(["ddddddd"]);
//$b = new Base();
//$beanList->add($b);
//$beanList->addAll(null, ["ffff" => "hhhh"]);
//Arr::new();
$set = $beanList->set("beanList2", $beanList2);
$beanList3 = UserBean::new(null, null);

$beanList4 = $beanList->get("beanList2");
$beanList2->setUserName("teste");
var_dump($beanList->toArr());


