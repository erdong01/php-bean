<?php


namespace Marstm\Support;


use Marstm\ArrayList;

trait Arr
{
    /**
     * @return mixed
     */
    public function getArray()
    {
        return $this->array;
    }

    /**
     * @param mixed $array
     */
    public function setArray($array): void
    {
        $this->array = $array;
    }

    private $array;

    public function isAssoc()
    {
        $array = $this->array;
        $keys = array_keys($array);

        return array_keys($keys) !== $keys;
    }



}