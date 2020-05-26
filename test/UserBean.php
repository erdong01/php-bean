<?php

namespace Marstm\Test;

require "../vendor/autoload.php";

use Marstm\Bean;

class Base
{

    private $a;
    protected $b;

    public $c;
}

class UserBean extends Base
{
    public function __construct($user_id, $user_name)
    {
        $this->setUserId($user_id);
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

$userBean = UserBean::new(0, "testest");
//$userBean->setUserName("teset");
//$userBean->setUserId(111);
$userBean->setIsRegister(false);
var_dump($userBean->getIsRegister());

//$userBean1 = UserBean::new()->setField("user.");
//var_dump($userBean1->toArr());
//var_dump($userBean->toArr());
//
//$userBean2 = UserBean::bind(["user_id" => 12, "user_name" => "new"]);
//var_dump($userBean2->toArr());

