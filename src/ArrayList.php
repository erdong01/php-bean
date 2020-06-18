<?php

namespace Marstm;

use Marstm\Support\Arrayable;
use Marstm\Support\Macroable;
use stdClass;
use Countable;
use Exception;
use ArrayAccess;
use Traversable;
use ArrayIterator;
use CachingIterator;
use JsonSerializable;
use IteratorAggregate;
use \Marstm\Support\Arr;
use Marstm\Support\Bean;

class ArrayList implements ArrayAccess, Countable, IteratorAggregate, JsonSerializable, Arrayable
{
    use  Arr;
    use Macroable;

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
        // TODO: Implement getIterator() method.
    }

    public function offsetExists($offset)
    {
        // TODO: Implement offsetExists() method.
    }

    public function offsetGet($offset)
    {
        // TODO: Implement offsetGet() method.
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
        // TODO: Implement offsetUnset() method.
    }

    public function count()
    {
        // TODO: Implement count() method.
    }

    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
    }

    /**
     * Get the collection of items as a plain array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_map(function ($value) {
            return $value instanceof Arrayable ? $value->toArray() : $value;
        }, $this->items);
    }
}