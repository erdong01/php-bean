<?php

namespace Marstm\Support\Traits;

use Illuminate\Support\Collection;
use Marstm\ArrayList;
use Marstm\Container\Container;
use Marstm\Support\I\Arrayable;

/**
 * Trait Arr
 * @package Marstm\Support\Traits
 */
trait Arr
{
    use Container, EnumeratesValues;

    private $array;

    /**
     * @param $e
     * @param null $type
     * @return array|array[]|mixed|void
     */
    private function objectArray($e, $type = null)
    {
        $arr = [];
        if (is_object($e)) {
            if ($this->isBean($e)) {
                $arr = $this->beanToArr($e);
            } else if (is_object($e)) {
                $arr = get_object_vars($e);
            }
            if ($type == 3) {
                $this->items[] = $arr;
                return;
            }
            return $arr;
        }
        $result = [];
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
            } else if ($type === 3) {
                $this->items[$k] = $arr;
            } else if ($type === 2) {
                array_splice($this->items, $this->index, 0, $arr);
            } else {
                $result[$k] = $arr;
            }
        }
        if ($type !== null) {
            return;
        }
        return $result;
    }

    /**
     * @return array
     */
    public function toArr()
    {
        return $this->items;
    }

    public function all()
    {
        return $this->items;
    }

    public function count()
    {
        return count($this->items);
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

    public function isAssoc()
    {
        $array = $this->items;
        $keys = array_keys($array);

        return array_keys($keys) !== $keys;
    }

    /**
     * Determine whether the given value is array accessible.
     *
     * @param mixed $value
     * @return bool
     */
    public static function accessible($value)
    {
        return is_array($value) || $value instanceof ArrayAccess;
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
     * Get the sum of the given values.
     * @param null $callback
     * @return float|int|mixed
     */
    public function sum($callback = null)
    {
        if (is_null($callback)) {
            return array_sum($this->getItems());
        }

        if (is_string($callback)) {
            return array_sum(array_column($this->getItems(), $callback));
        }
        $callback = $this->valueRetriever($callback);
        if (is_object($callback)) {
            return $this->reduce(function ($result, $item) use ($callback) {
                return $result + $callback($item);
            }, 0);
        }
        return array_sum($callback);
    }

    /**
     * Reduce the collection to a single value.
     * @param callable $callable
     * @param null $initial
     * @return mixed
     */
    public function reduce(callable $callable, $initial = null)
    {
        return array_reduce($this->items, $callable, $initial);
    }

    /**
     * Determine if the given value is callable, but not a string.
     * @param $value
     * @return bool
     */
    protected function useAsCallable($value)
    {
        return !is_string($value) && is_callable($value);
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
     * Collapse an array of arrays into a single array.
     * @param iterable $array
     * @return mixed
     */
    public static function collapse($array)
    {
        $results = [];

        foreach ($array as $values) {
            if ($values instanceof ArrayList) {
                $values = $values->all();
            } elseif (!is_array($values)) {
                continue;
            }
            $results[] = $values;
        }
        return array_merge([], ...$results);
    }

    public function combine($values)
    {
        $this->items = array_combine($this->all(), $this->objectArray($values));
        return $this;
    }

    public function concat($values)
    {
        foreach ($values as $item) {
            $this->items[] = $item;
        }
        return $this;
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

    /**
     * Cross join the given arrays, returning all possible permutations.
     * @param mixed ...$arrays
     * @return array|array[]
     */
    public static function crossJoin(...$arrays)
    {
        $results = [[]];

        foreach ($arrays as $index => $array) {
            $append = [];

            foreach ($results as $product) {
                foreach ($array as $item) {
                    $product[$index] = $item;

                    $append[] = $product;
                }
            }

            $results = $append;
        }
        return $results;
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
     * Determine if the given key exists in the provided array.
     * @param $array
     * @param $key
     * @return bool
     */
    public static function exists($array, $key)
    {
        if ($array instanceof \ArrayAccess) {
            return $array->offsetExists($key);
        }
        return array_key_exists($key, $array);
    }

    /**
     * Get and remove the first item from the collection.
     *
     * @return mixed
     */
    public function shift()
    {
        return array_shift($this->items);
    }

    /**
     * return value($default);
     * @param $array
     * @param callable|null $callback
     * @param null $default
     * @return mixed
     */
    public function first(callable $callback = null, $default = null)
    {
        $array = $this->items;
        if (is_null($callback)) {
            if (empty($array)) {
                return bean_value($default);
            }
            foreach ($array as $item) {
                return $item;
            }
        }
        foreach ($array as $key => $value) {
            if (call_user_func($callback, $value, $key)) {
                return $value;
            }
        }
        return bean_value($default);
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
     * Get an operator checker arrayList.
     * @param $key
     * @param $operator
     * @param null $value
     * @return \Closure
     */
    public function operatorForWhere($key, $operator, $value = null)
    {
        if (func_num_args() == 2) {
            $value = $operator;
            $operator = "=";
        }
        return function ($item) use ($key, $operator, $value) {
            $retrieved = $key;
            $strings = array_filter([$retrieved, $value], function ($value) {
                return is_string($value) || (is_object($value)) && method_exists($value, '__toString');
            });
            if (count($strings) < 2 && count(array_filter([$key, $value], 'is_object')) == 1) {
                return in_array($operator, ['!=', '<>', '!==']);
            }
            switch ($operator) {
                default:
                case '=':
                case '==':
                    return $retrieved == $value;
                case '!=':
                case '<>':
                    return $retrieved != $value;
                case '<':
                    return $retrieved < $value;
                case '>':
                    return $retrieved > $value;
                case '<=':
                    return $retrieved <= $value;
                case '>=':
                    return $retrieved >= $value;
                case '===':
                    return $retrieved === $value;
                case '!==':
                    return $retrieved !== $value;
            }
        };
    }

    /**
     * Filter the array using the given callback.
     *
     * @param array $array
     * @param callable $callback
     * @return array
     */
    public static function where($array, callable $callback)
    {
        return array_filter($array, $callback, ARRAY_FILTER_USE_BOTH);
    }
}