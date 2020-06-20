<?php

namespace Marstm\Support\Traits;

use Marstm\ArrayList;
use Marstm\Support\I\Arrayable;

/**
 * Trait EnumeratesValues
 * @package Marstm\Support\Traits
 */
trait EnumeratesValues
{

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

}