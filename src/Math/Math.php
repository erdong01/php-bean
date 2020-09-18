<?php


namespace Marstm\Math;


trait Math
{

    public function add($left_operand, $right_operand, $scale = 0)
    {
        $res = bcadd($left_operand, $right_operand);
        $this->{$left_operand} = $res;
        return $res;
    }
}