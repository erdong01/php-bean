<?php

use \Marstm\ArrayList;

if (!function_exists('arrayList')) {
    function arrayList(...$items)
    {
        return ArrayList::new(...$items);
    }
}

if (!function_exists('bean_value')) {

    function bean_value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}

if (!function_exists('data_get')) {
    /**
     * Get an item from an array or object using "dot" notation.
     * @param $target
     * @param $key
     * @param null $default
     * @return mixed
     */
    function bean_data_get($target, $key, $default = null)
    {
        if (is_null($key)) {
            return $target;
        }
        $key = is_array($key) ? $key : explode('.', $key);
        while (!is_null($segment = array_shift($key))) {
            if ($segment === '*') {
                if ($target instanceof ArrayList) {
                    $target = $target->all();
                } elseif (!is_array($target)) {
                    return bean_value($default);
                }
            }
        }
        return $target;
    }
}