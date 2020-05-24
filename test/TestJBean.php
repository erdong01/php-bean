<?php

namespace Marstm\Test;

require "../vendor/autoload.php";

use Marstm\Bean;

class TestJBean
{
    use Bean;

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


    /**
     * @var int
     */
    public $user_id;
    /**
     * @var string
     */
    public $user_name;

}

$test = TestJBean::new();
$test->setUserName("teset");
$test->setUserId(111);

var_dump($test->toArray());

$test1 = TestJBean::new()->setField("user.");
var_dump($test1->toArray());
var_dump($test->toArray());