<?php


namespace Marstm\Support;


use Marstm\Container\Container;

trait Arr
{
    use Container;

    private $array;

    public function isAssoc()
    {
        $array = $this->items;
        $keys = array_keys($array);

        return array_keys($keys) !== $keys;
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
            return array_sum($this->items);
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

    protected function valueRetriever($value)
    {
        if ($this->useAsCallable($value)) {
            return $value;
        }
        if (is_string($value)) {
            return array_column($this->items, $value);
        }
        return;
    }

}