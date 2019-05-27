<?php

namespace PhpType\Model;


trait BeanModel
{
    /**
     * 将下划线命名转换为驼峰式命名
     * @param $str
     * @param bool $ucfirst
     * @return mixed|string
     * @author chenqiaojie 2018-05-14
     */
    private static function convertUnder($str, $ucfirst = true)
    {
        $str = ucwords(str_replace('_', ' ', $str));
        $str = str_replace(' ', '', lcfirst($str));
        return $ucfirst ? ucfirst($str) : $str;
    }

    /**
     * 绑定数据
     * @return $this
     * @author chenqiaojie 2019-05-27
     */
    public function bindData()
    {
        $classAttr = $this->attributes;
        foreach ($classAttr as $key => $value) {
            $func = 'set' . self::convertUnder($key);
            if ($value) {
                $this->$func($value);
            }
        }
        return $this;
    }
}