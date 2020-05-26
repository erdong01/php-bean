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
     * 加载获得实例
     * @param bool $select 要获取查询字段
     * @return object|static|null
     */
    public static function new(bool $select = false)
    {
        $instance = new static();
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
    public function toArray()
    {
        $arr = get_object_vars($this->instance);  //对象属性转数组
        $arr = array_filter($arr, function ($v, $k) {
            return !empty($v);
        }, ARRAY_FILTER_USE_BOTH); //过滤掉为空的数组
        $this->unset($arr);
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