<?php


namespace Marstm\Math;


trait Math
{

    public function add($column, $amount = 1, $scale = 0)
    {

        return $this->{$column} = bcadd($this->{$column}, $amount, $scale);
    }
}