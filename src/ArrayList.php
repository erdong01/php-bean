<?php

namespace Marstm;

use Marstm\Support\I\Arrayable;
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

    /**
     * ArrayList constructor.
     * @param array $items
     * @param int $type
     */
    public function __construct($items = [])
    {
        $this->objectArray($items, 3);
    }

    /**
     * @param array $items
     * @return ArrayList
     */
    public static function new($items = [])
    {
        return new self($items);
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
     * @return $this
     */
    public function collapse()
    {
        $this->items = Arr::collapse($this->items);
        return $this;
    }

    /**
     * Count the number of items in the collection using a given truth test.
     */
    public function countBy($callback = null)
    {
        if (is_null($callback)) {
            $callback = function ($value) {
                return $value;
            };
        }
        return $this->groupBy($callback)->map(function ($value) {
            return $value->count();
        });
    }

    /**
     * Cross join with the given lists, returning all possible permutations.
     * @param mixed ...$lists
     * @return $this
     */
    public function crossJoin(...$lists)
    {
        $this->setItems(Arr::crossJoin($this->getItems(), ...array_map([$this, 'getArrayableItems'], $lists)));
        return $this;
    }

    /**
     * Retrieve duplicate items from the collection.
     *
     * @param callable|null $callback
     * @param bool $strict
     * @return static
     */
    public function duplicates($callback = null, $strict = false)
    {
        $items = $this->map($this->valueRetriever($callback));
        $uniqueItems = $items->unique(null, $strict);

        $compare = $this->duplicateComparator($strict);
        $duplicates = new static();

        foreach ($items as $key => $value) {
            if ($uniqueItems->isNotEmpty() && $compare($value, $uniqueItems->first())) {
                $uniqueItems->shift();

            } else {
                $duplicates->items[$key] = $value;
            }
        }
        return $duplicates;

    }

    /**
     * Execute a callback over each item.
     * @param callable $callback
     * @return $this
     */
    public function each(callable $callback)
    {
        foreach ($this as $key => $item) {
            if ($callback($item, $key) === false) {
                break;
            }
        }
        return $this;
    }
    /**
     * Determine if all items pass the given truth test.
     *
     * @param  string|callable  $key
     * @param  mixed  $operator
     * @param  mixed  $value
     * @return bool
     */
    public function every($key, $operator = null, $value = null)
    {
        if (func_num_args() === 1) {
            $callback = $this->valueRetriever($key);

            foreach ($this as $k => $v) {
                if (! $callback($v, $k)) {
                    return false;
                }
            }

            return true;
        }

        return $this->every($this->operatorForWhere(...func_get_args()));
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
                $groupKeyArr = $groupByV($value, $key);
            } else {
                $groupKeyArr = [$value[$groupBy]];
            }
            if (!is_array($groupKeyArr)) {
                $groupKeyArr = [$groupKeyArr];
            }
            foreach ($groupKeyArr as $groupKey) {
                if (!array_key_exists($groupKey, $results)) {
                    $results[$groupKey] = new static;
                }
                $results[$groupKey]->offsetSet($preserveKeys ? $key : null, $value);
            }
        }
        $result = new static();
        $result->setItems($results);
        if (!empty($nextGroups)) {
            return $result->map->groupBy($nextGroups, $preserveKeys);
        }
        return $result;
    }


    /**
     * Run a filter over each of the items.
     * @param callable|null $callback
     * @return $this
     */
    public function filter(callable $callback = null)
    {
        if ($callback) {
            return new static(Arr::where($this->getItems(), $callback));
        }
        return new static(array_filter($this->getItems()));
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