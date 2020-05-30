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
    public static function new($item = [])
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


}