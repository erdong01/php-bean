<?php

namespace Marstm;

/**
 * Class Bean
 * @package PhpType
 */
trait Bean
{
    private $instance;

    /**
     * 设置字段名
     * @param string $prefix
     * @return object|static|null
     */
    public function setField(string $prefix = "")
    {
        $classAttr = $this->instance->getClassAttr();
        foreach ($classAttr as $key => $value) {
            $this->instance->$key = $prefix . $key;
        }
        return $this->instance;
    }

    /**
     * @param mixed ...$columns
     * @return object|static|null
     */
    public static function new(...$columns)
    {
        $instance = new static(...$columns);
        $instance->instance = $instance;
        return $instance;
    }

    /**
     * 绑定数据
     * @param array $data
     * @return object|static
     */
    public static function bind($data = [])
    {
        $instance = new static();
        $instance->instance = $instance;
        $classAttr = $instance->instance->getClassAttr();
        foreach ($classAttr as $key => $value) {
            $func = 'set' . $instance::convertUnder($key);
            if (!function_exists($func) || !property_exists($key)) {
                continue;
            }
            if (isset($data[$key])) {
                $instance->instance->$func($data[$key]);
            } else if (isset($data->$key)) {
                $instance->instance->$func($data->$key);
            }
        }
        return $instance->instance;
    }

    /**
     * 将下划线命名转换为驼峰式命名
     * @param $str
     * @param bool $ucfirst
     * @return mixed|string
     */
    public static function convertUnder($str, $ucfirst = true)
    {
        $str = ucwords(str_replace('_', ' ', $str));
        $str = str_replace(' ', '', lcfirst($str));
        return $ucfirst ? ucfirst($str) : $str;
    }

    /**
     * 转换数组
     * @return array
     */
    public function toArr()
    {
        $arr = [];  //对象属性转数组
        $classAttr = $this->getClassAttr();
        foreach ($classAttr as $key => $value) {
            $func = 'get' . $this->convertUnder($key);
            if (!method_exists($this, $func) || !property_exists($this, $key)) {
                continue;
            }
            $val = $this->$func();
            if ($val !== null) {
                $arr[$key] = $val;
            }
        }
        return $arr;
    }

    /**
     * 获取对象属性
     * @return array
     */
    private function getClassAttr()
    {
        $arr = get_object_vars($this->instance); //对象属性转数组
        $this->unset($arr);
        return $arr;
    }

    /**
     * @param $arr
     */
    private function unset(&$arr)
    {
        unset($arr['instance']);
        unset($arr['instanceArr']);
    }


}