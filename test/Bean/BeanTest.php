<?php


namespace Marstm\Test\Bean;


use Marstm\Test\Models\User;
use PHPUnit\Framework\TestCase;

class BeanTest extends TestCase
{

    public function testArrayList()
    {

        $user = User::ArrayList();
        $user->bean()->setUserId(11);
        $user->bean()->setUserName("name1");
        $user->push();
        $user->bean()->setUserId(22);
        $user->bean()->setUserName("name2");
        $user->push();
        $user->bean()->setUserId(22);
        $user->bean()->setUserName("name3");
        $user->push();
        $user = $user->where("user_id", 22);
        $Buser->bean()->setUserName("name23");
        $Buser = $Buser->update();
        $user->update();
        $this->assertTrue(true);
    }
}