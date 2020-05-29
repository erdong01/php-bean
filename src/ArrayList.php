<?php

namespace Marstm;

use \Marstm\Support\Arr;
use Marstm\Support\Bean;

class ArrayList
{
    use  Arr;

    private $index;

    /**
     * @param mixed ...$item
     * @return ArrayList
     */
    public static function new($item)
    {
        $i = new self();
        $i->objectArray($item, 1);
        return $i;
    }

    /**
     * @param $index
     * @param $bean
     * @return mixed|void
     */
    public function get($index, &$bean = null)
    {
        if (!empty($bean)) {
            $bean = $this->items[$index];
            return;
        }
        return $this->items[$index];
    }

    public function set($index, $element)
    {
        return $this->items[$index] = $this->objectArray($element);
    }

    public function add(...$object)
    {
        $this->objectArray($object, 1);
        return;
    }

    public function addAll($index, ...$element)
    {
        $this->index = $index;
        $this->objectArray($element, 2);
        return;
    }

    public function remove($index)
    {
        unset($this->items[$index]);
    }

    public function size()
    {
        return count($this->items);
    }

    public function isEmpty($index = null)
    {
        if ($index) {
            if (isset($this->items[$index]) && empty($this->items[$index])) {
                return true;
            }
        } else {
            if (count($this->items)) {
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
        $this->items = [];

    }

    /**
     * @return mixed
     */
    public function pop()
    {
        return array_pop($this->items);
    }

    /**
     * @return array
     * @author chenqiaojie 2020-05-28
     */
    public function toArr()
    {
        return $this->items;
    }

    private function objectArray($e, $type = null)
    {
        if (is_array($e)) {
            $this->items = $e;
            return;
        }
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
                $arr = $this->beanToArr($v);
            } else if (is_object($v)) {
                $arr = get_object_vars($v);
            } else {
                $arr = $v;
            }
            if ($type === 1) {
                array_unshift($this->items, $arr);
            } else if ($type === 2) {
                array_splice($this->items, $this->index, 0, $arr);
            }
        }
        return;
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