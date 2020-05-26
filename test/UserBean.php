<?php

namespace Marstm\Test;

require "../vendor/autoload.php";

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

$userBean = UserBean::new();
$userBean->setUserName("teset");
$userBean->setUserId(111);
var_dump($userBean->toArray());

$userBean1 = UserBean::new()->setField("user.");
var_dump($userBean1->toArray());
var_dump($userBean->toArray());

$userBean2 = UserBean::bind(["user_id" => 12, "user_name" => "new"]);
var_dump($userBean2->toArray());

