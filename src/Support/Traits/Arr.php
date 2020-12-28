<?php

namespace Marstm\Support\Traits;

use ArrayAccess;
use Marstm\ArrayList;
use Marstm\Container\Container;
use Marstm\Support\I\Arrayable;

/**
 * Trait Arr
 * @package Marstm\Support\Traits
 */
trait Arr
{

    /**
     * Determines if an array is associative.
     * An array is "associative" if it doesn't have sequential numerical keys beginning with zero.
     * @param array $array
     * @return bool
     */
    public function isAssoc(array $array)
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
     * return value($default);
     * @param $array
     * @param callable|null $callback
     * @param null $default
     * @return mixed
     */
    public static function first($array, callable $callback = null, $default = null)
    {
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
     * Filter the array using the given callback.
     *
     * @param array $array
     * @param callable $callback
     * @return array
     */
    public static function where(array $array, callable $callback): array
    {
        return array_filter($array, $callback, ARRAY_FILTER_USE_BOTH);
    }


}