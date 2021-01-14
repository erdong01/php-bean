<?php

namespace Marstm\Test\Models;

use Marstm\Support\Traits\Bean;

class User extends Base
{
    use Bean;

    /**
     * @return int
     */
    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
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
     * @return float|null
     */
    public function getM(): ?float
    {
        return $this->m;
    }

    /**
     * @param float|null $m
     */
    public function setM(?float $m): void
    {
        $this->m = $m;
    }

    /**
     * @return array
     */
    public function getRole(): array
    {
        return $this->role;
    }

    /**
     * @param array $role
     */
    public function setRole(array $role): void
    {
        $this->role = $role;
    }

    /**
     * 用户id
     * @var int #整型
     */
    private int|null $user_id;
    /**
     * 用户名
     * @var string|null #字符串类型
     */
    private string $user_name;

    /**
     * @var bool|null
     */
    private bool $is_register;
    /**
     * @var float|null
     */
    private float $m;
    private array $role;
}