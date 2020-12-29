<?php

namespace Marstm;

use Marstm\Support\I\Arrayable;
use Marstm\Support\I\Enumerable;
use Marstm\Support\Traits\Arr;
use Marstm\Support\Traits\EnumeratesValues;
use Marstm\Support\Traits\Macroable;
use ArrayAccess;
use ArrayIterator;

/**
 * Class ArrayList
 * @package Marstm
 */
class ArrayList implements ArrayAccess, Enumerable
{
    use   Macroable, EnumeratesValues;

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
        if (empty($items)) return;
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
     * Get the comparison function to detect duplicates.
     *
     * @param bool $strict
     * @return \Closure
     */
    public function duplicateComparator($strict)
    {
        if ($strict) {
            return function ($a, $b) {
                return $b === $a;
            };
        }
        return function ($a, $b) {
            return $a == $b;
        };
    }

    /**
     * Get the items in the arr whose keys are not present in the given items.
     * @param $items
     * @return $this
     */
    public function diffKeys($items)
    {
        $this->setItems(array_diff_key($this->getItems(), $this->getArrayableItems($items)));
        return $this;
    }

    /**
     * Get the items in the arrayList whose keys and values are not present in the given items.
     * @param $items
     * @return $this
     */
    public function diffAssoc($items)
    {
        $this->setItems(array_diff_assoc($this->getItems(), $this->getArrayableItems($items)));
        return $this;
    }

    /**
     * Get the items in the collection that are not present in the given items.
     * @param $items
     * @return $this
     */
    public function diff($items)
    {
        $this->setItems(array_diff($this->getItems(), $this->getArrayableItems($items)));
        return $this;
    }

    /**
     * Determine if an item exists in the collection.
     *
     * @param mixed $key
     * @param mixed $operator
     * @param mixed $value
     * @return bool
     */
    public function contains($key, $operator = null, $value = null)
    {
        if (func_num_args() == 1) {
            if ($this->useAsCallable($key)) {
                $placeholder = new \stdClass();
                return $this->first($key, $placeholder) !== $placeholder;
            }
            return in_array($key, $this->getItems());
        }
        return $this->contains($this->operatorForWhere(...func_get_args()));
    }

    public function concat($values)
    {
        foreach ($values as $item) {
            $this->items[] = $item;
        }
        return $this;
    }

    public function combine($values)
    {
        $this->items = array_combine($this->all(), $this->objectArray($values));
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
     * @param string|callable $key
     * @param mixed $operator
     * @param mixed $value
     * @return bool
     */
    public function every($key, $operator = null, $value = null)
    {
        if (func_num_args() === 1) {
            $callback = $this->valueRetriever($key);

            foreach ($this as $k => $v) {
                if (!$callback($v, $k)) {
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
     * clear
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
        $new = new self();
        $new->setInstance($this->getInstance());
        if ($callback) {
            $new->setItems(Arr::where($this->getItems(), $callback));
            return $new;
        }
        $new->setItems(Arr::where($this->getItems(), $callback));
        return $new;
    }

    /**
     * Chunk the underlying collection array.
     *
     * @param int $size
     * @return static
     */
    public function chunk($size)
    {
        if ($size <= 0) {
            return new self();
        }
        $this->items = array_chunk($this->items, $size);
        return $this;
    }

    /**
     * Get the average value of a given key.
     *
     * @param callable|string|null $callback
     * @return mixed
     */
    public function avg($callback = null)
    {
        if ($count = count($this->items)) {
            return $this->sum($callback) / $count;
        }
    }

    /**
     * Reduce the collection to a single value.
     *
     * @param callable $callback
     * @param mixed $initial
     * @return mixed
     */
    public function reduce(callable $callback, $initial = null)
    {
        return array_reduce($this->items, $callback, $initial);
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

    public function all()
    {
        return $this->items;
    }

    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
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

    public function count()
    {
        return count($this->items);
    }

    /**
     * Return the values from a single column in the input array
     * @param $column
     * @param null $index_key
     * @return $this
     */
    public function column($column, $index_key = null)
    {
        $this->items = array_column($this->items, $column, $index_key);
        return $this;
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
            if ($itemsV instanceof Arrayable) {
                $res[$itemsK] = $itemsV->toArray();
            } else {
                $res[$itemsK] = $itemsV;
            }
        }
        $this->items = array_combine($keys, $res);
        return $this;
    }

    /**
     *  Key an associative array by a field or using a callback.
     * @param $keyBy
     * @return $this
     */
    public function keyBy($keyBy)
    {
        if (is_string($keyBy)) {
            $this->items = array_column($this->items, null, $keyBy);
            return $this;
        }
        $keyBy = $this->valueRetriever($keyBy);
        if (is_object($keyBy)) {
            $results = [];
            foreach ($this->items as $key => $item) {
                $resolvedKey = $keyBy($item);
                if (is_object($resolvedKey)) {
                    $resolvedKey = (string)$resolvedKey;
                }
                $results[$resolvedKey] = $item;
            }
            $this->items = $results;
        }
        return $this;
    }

    /**
     * Get the first item from the collection passing the given truth test.
     *
     * @param callable|null $callback
     * @param mixed $default
     * @return mixed
     */
    public function first(callable $callback = null, $default = null)
    {
        return Arr::first($this->items, $callback, $default);
    }

    /**
     * @return object|static|null
     * @throws \Exception
     */
    public function firstBean(callable $callback = null, $default = null)
    {
        $instance = $this->getInstance();
        return $instance->bindData(Arr::first($this->items, $callback, $default));
    }

    public function push()
    {
        $instance = $this->getInstance();
        $this->add($instance->toArr());
        $this->clearInstance();
    }
}
