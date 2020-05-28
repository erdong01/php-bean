<?php


namespace Marstm;


class ArrayList
{
    /**
     * @var array
     */
    protected $beanList = [];

    public static function new()
    {
        return new static();
    }

    public function get($index)
    {
        return $this->beanList[$index];
    }

    public function add(...$object)
    {
        array_unshift($this->beanList, $object);
    }

    public function set($index, ...$element)
    {
        array_splice($this->beanList, $index, 0, $element);
    }

    public function remove($index)
    {
        unset($this->beanList[$index]);
    }

    public function size()
    {
        return count($this->beanList);
    }

    public function isEmpty($index = null)
    {
        if ($index) {
            if (isset($this->beanList[$index]) && empty($this->beanList[$index])) {
                return true;
            }
        } else {
            if (count($this->beanList)) {
                return true;
            }
        }
        return false;
    }

    /**
     * 清除
     */
    public function clear()
    {
        $this->beanList = [];

    }

    /**
     * @return mixed
     */
    public function pop()
    {
        return array_pop($this->beanList);
    }

    /**
     * @return array
     * @author chenqiaojie 2020-05-28
     */
    public function toArr()
    {
        $arr = [];
        foreach ($this->beanList as $v) {
            if (is_object($v) && method_exists($v, 'toArr')) {
                $arr[] = $v->toArr();
            } else if (is_object($v)) {
                $arr[] = get_object_vars($v);
            } else {
                $arr[] = $v;
            }
        }
        return $arr;
    }
}