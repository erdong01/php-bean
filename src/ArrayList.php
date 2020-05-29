<?php

namespace Marstm;

use \Marstm\Support\Arr;
use Marstm\Support\Bean;

class ArrayList
{
    use  Arr;

    public static function new()
    {
        return new self();
    }

    /**
     * @param $index
     * @param $bean
     * @return mixed|void
     */
    public function get($index, &$bean = null)
    {
        if (!empty($bean)) {
            $bean = $this->beanList[$index];
            return;
        }
        return $this->beanList[$index];
    }

    public function set($index, $element)
    {

        return $this->beanList[$index] = $element;
    }

    public function add(...$object)
    {
        array_unshift($this->beanList, $object);
    }

    public function addAll($index, ...$element)
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
        return $this->objectArray($this->beanList);
    }

    private function objectArray($e)
    {
        $arr = [];
        if (is_object($e)) {
            if ($this->isBean($e)) {
                $arr = $this->beanToArr($e);
            } else if (is_object($e)) {
                $arr = get_object_vars($e);
            }
            return $arr;
        }

        foreach ($e as $k => $v) {
            if ($this->isBean($v)) {
                $arr[$k] = $this->beanToArr($v);
            } else if (is_object($v)) {
                $arr[$k] = get_object_vars($v);
            } else {
                $arr[$k] = $v;
            }
        }
        return $arr;
    }

    public function beanToArr($object)
    {
        return $object->toArr();
    }

    public function isBean($object)
    {
        if (is_object($object) && method_exists($object, 'toArr')) {
            return true;
        }
        return false;
    }
}