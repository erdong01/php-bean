<?php

namespace Marstm;

use Marstm\Support\I\Enumerable;
use Marstm\Support\Traits\Macroable;
use ArrayAccess;
use ArrayIterator;
use Marstm\Support\Traits\Arr;

/**
 * Class ArrayList
 * @package Marstm
 */
class ArrayList implements ArrayAccess, Enumerable
{
    use  Arr, Macroable;

    /**
     * @var  void|null
     */
    private $index;

    public function __construct($items = [])
    {
        $this->items = $items;
    }

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

    /**
     * @param $index
     * @param $element
     * @return array|mixed
     */
    public function set($index, $element)
    {
        return $this->items[$index] = $this->objectArray($element);
    }

    /**
     * @param mixed ...$object
     */
    public function add(...$object)
    {
        $this->objectArray($object, 1);
        return;
    }

    /**
     * @param $index
     * @param mixed ...$element
     */
    public function addAll($index, ...$element)
    {
        $this->index = $index;
        $this->objectArray($element, 2);
        return;
    }

    /**
     * @param $index
     */
    public function remove($index)
    {
        unset($this->items[$index]);
    }

    /**
     * @return int
     */
    public function size()
    {
        return count($this->items);
    }

    /**
     * @param null $index
     * @return bool
     */
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
     * clear 清除
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
     * Group an associative array by a field or using a ArrayList.
     * @param $groupBy
     * @param bool $preserveKeys
     * @return $this|void
     */
    public function groupBy($groupBy, $preserveKeys = false)
    {
        if (is_string($groupBy)) {
            $groupKeys[] = $groupBy;
        }

        if (is_array($groupBy)) {
            $nextGroups = $groupBy;
            $groupBy = array_shift($nextGroups);
            if (!$groupBy) return;
        }

        $results = [];
        $groupByV = $this->valueRetriever($groupBy);
        foreach ($this->items as $key => $value) {
            if (is_object($groupByV)) {
                $groupKeyArr = $groupByV($value);
            } else {
                $groupKeyArr = [$value[$groupBy]];
            }
            foreach ($groupKeyArr as $groupKey) {

                if (!array_key_exists($groupKey, $results)) {
                    $results[$groupKey] = new static;
                }
                $results[$groupKey]->offsetSet($preserveKeys ? $key : null, $value);
            }

        }

        $result = new static($results);
        if (!empty($nextGroups)) {
            return $result->map->groupBy($nextGroups, $preserveKeys);
        }
        return $result;
    }

    /**
     * Run a map over each of the items.
     *
     * @param callable $callback
     * @return static
     */
    public function map(callable $callback)
    {
        $keys = array_keys($this->items);
        $items = array_map($callback, $this->items, $keys);
        $res = [];
        foreach ($items as $itemsK => $itemsV) {
            $res[$itemsK] = $itemsV->toArray();
        }

        $this->items = array_combine($keys, $res);
        return $this;
    }

    /**
     * Dynamically access collection proxies.
     *
     * @param string $key
     * @return mixed
     *
     * @throws \Exception
     */
    public function __get($key)
    {
        return new \Marstm\Support\HigherOrderCollectionProxy($this, $key);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }

    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->items);
    }

    public function offsetGet($offset)
    {
        $this->items[$offset];
    }

    public function offsetSet($key, $value)
    {
        if (is_null($key)) {
            $this->items[] = $value;
        } else {
            $this->items[$key] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

}