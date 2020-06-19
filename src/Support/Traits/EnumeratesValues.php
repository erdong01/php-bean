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
            return $items->items;
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
}