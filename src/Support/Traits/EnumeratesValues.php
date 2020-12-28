<?php

namespace Marstm\Support\Traits;

use Marstm\ArrayList;
use Marstm\Container\Container;
use Marstm\Support\I\Arrayable;

/**
 * Trait EnumeratesValues
 * @package Marstm\Support\Traits
 */
trait EnumeratesValues
{
    use Arr, Container;

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
     * Results array of items from ArrayList or Arrayable.
     * @param $items
     * @return array
     * @author chenqiaojie 2020-06-19
     */
    protected function getArrayableItems($items): array
    {
        if (is_array($items)) {
            return $items;
        } elseif ($items instanceof ArrayList) {
            return $items->getItems();
        }
        return (array)$items;
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_map(function ($value) {
            if ($value instanceof \JsonSerializable) {
                return $value->jsonSerialize();
            } elseif ($value instanceof Arrayable) {
                return $value->toArray();
            }
            return $value;
        }, $this->all());
    }


    /**
     * @param $value
     * @return \Closure
     */
    protected function valueRetriever($value)
    {
        if ($this->useAsCallable($value)) {
            return $value;
        }

        return function ($item) use ($value) {
            return bean_data_get($item, $value);
        };
    }

    /**
     * @param null $key
     * @param bool $strict
     * @return EnumeratesValues
     */
    public function unique($key = null, $strict = false)
    {
        $callback = $this->valueRetriever($key);
        $exists = [];
        $res = $this->reject(function ($item, $key) use ($callback, $strict, &$exists) {
            if (in_array($id = $callback($item, $key), $exists, $strict)) {
                return true;
            }
            $exists[] = $id;
        });
        return $res;

    }

    /**
     * Create a collection of all elements that do not pass a given truth test.
     *
     * @param callable|mixed $callback
     * @return static
     */
    public function reject($callback = true)
    {
        $useAsCallable = $this->useAsCallable($callback);

        return $this->filter(function ($value, $key) use ($callback, $useAsCallable) {
            return $useAsCallable
                ? !$callback($value, $key)
                : $value != $callback;
        });
    }

    /**
     * Determine if the collection is not empty.
     *
     * @return bool
     */
    public function isNotEmpty()
    {
        return !$this->isEmpty();
    }

    /**
     * Filter items by the given key value pair.
     *
     * @param string $key
     * @param mixed $operator
     * @param mixed $value
     * @return static
     */
    public function where($key, $operator = null, $value = null)
    {
        return $this->filter($this->operatorForWhere(...func_get_args()));
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

    public function beanToArr($object)
    {
        return $object->toArr();
    }

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

    public function isBean($object)
    {
        if (is_object($object) && method_exists($object, 'toArr')) {
            return true;
        }
        return false;
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
        if (func_num_args() === 1) {
            $value = true;

            $operator = '=';
        }

        if (func_num_args() == 2) {
            $value = $operator;
            $operator = "=";
        }
        return function ($item) use ($key, $operator, $value) {
            $retrieved = bean_data_get($item, $key);
            $strings = array_filter([$retrieved, $value], function ($value) {
                return is_string($value) || (is_object($value) && method_exists($value, '__toString'));
            });

            if (count($strings) < 2 && count(array_filter([$retrieved, $value], 'is_object')) == 1) {
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

}
