<?php


namespace Marstm\Support;


use Illuminate\Support\Collection;
use Marstm\Container\Container;

trait Arr
{
    use Container;

    private $array;

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

    private function objectArray($e, $type = null)
    {
        if (is_array($e)) {
            if ($type === 1) {
                $this->items = $e;
            }
            return $e;
        }
        $arr = [];
        if (is_object($e)) {
            if ($this->isBean($e)) {
                $arr = $this->beanToArr($e);
            } else if (is_object($e)) {
                $arr = get_object_vars($e);
            }
            return $arr;
        }
        $results = [];
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


            } else if ($type === 2) {
                array_splice($this->items, $this->index, 0, $arr);
            }
            $results[$k] = $arr;
        }
        return $results;
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
        if (is_string($callback)) {
            return array_column($this->items, $callback);
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
     * @param $value
     * @return array|void
     */
    protected function valueRetriever($value)
    {
        if ($this->useAsCallable($value)) {
            return $value;
        }
        return;
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

    public function collapse()
    {
        $array = $this->items;
        $resultArr = [];
        foreach ($array as $values) {
            if (is_array($values)) {
                $resultArr = array_merge($resultArr, $values);
            } else {
                $this->objectArray($values);
            }
        }
        $this->items = $resultArr;
        return $this;
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


    public function groupBy($groupBy, $preserveKeys = false, &$results = [])
    {
        if (is_string($groupBy)) {
            $groupKeys[] = $groupBy;
        }
        if (is_array($groupBy)) {
            $nextGroups = $groupBy;
            $groupBy = array_shift($nextGroups);
            if (!$groupBy) return;
        }

        $groupByV = $this->valueRetriever($groupBy);
        foreach ($this->items as $key => $value) {
            if (is_object($groupByV)) {
                $groupKeyArr = $groupByV($value);
            } else {
                $groupKeyArr = [$value[$groupBy]];

            }
            foreach ($groupKeyArr as $groupKey) {

                if ($preserveKeys == false) {
                    $results[$groupKey][] = $value;
                } else {
                    $results[$groupKey][$key] = $value;
                }

            }

        }
        var_dump($results);
        die;
        if (!empty($nextGroups)) {
            $this->groupBy($nextGroups, $preserveKeys, $results);
        }

//        $this->items = $results;
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
        $this->items = array_combine($keys, $items);

        return $this;
    }
}