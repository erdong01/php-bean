<?php

namespace Mars;


/**
 * Class Bean
 * @package PhpType
 */
trait Bean
{
    private $instance;
    private $prefix;


    /**
     * 设置字段名
     * @param $name
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
     * @author chenqiaojie 2018-05-16
     */
    public static function new(bool $select = false)
    {
        $instance = new static();
        $instance->instance = $instance;
        return $instance;
    }

    /**
     * @param array $data
     * @param null $abstract
     * @return object|static|null
     */
    public static function bind($data = [], $abstract = null)
    {
        $instance = new static();
        $instance->instance = $instance;
        $classAttr = $instance->getClassAttr();
        foreach ($classAttr as $key => $value) {
            $func = 'set' . $instance::convertUnder($key);
            if (isset($data->$key)) {
                $instance->$func($data->$key);
            }
        }
        return $instance;
    }

    /**
     * 将下划线命名转换为驼峰式命名
     * @param $str
     * @param bool $ucfirst
     * @return mixed|string
     * @author chenqiaojie 2018-05-14
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
     * @author chenqiaojie 2018-08-07
     */
    public function toArray()
    {
        $arr = get_object_vars($this->instance);  //对象属性转数组
        $arr = array_filter($arr, function ($v, $k) {
            return !empty($v);
        }, ARRAY_FILTER_USE_BOTH); //过滤掉为空的数组
        unset($arr['instance']);
        unset($arr['instanceArr']);
        return $arr;
    }

    /**
     * 获取对象属性
     * @return array
     * @author chenqiaojie 2018-08-07
     */
    private function getClassAttr()
    {
        $arr = get_object_vars($this->instance); //对象属性转数组
        unset($arr['instance']);
        unset($arr['instanceArr']);
        return $arr;
    }


}