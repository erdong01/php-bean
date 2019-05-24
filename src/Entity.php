<?php

namespace PhpType;


/**
 * 实例操作
 * Class Entity
 * @package phpType
 */
class Entity
{
    protected $instance;

    /**
     * 填充字段
     * @var array
     */
    public static $fillable;

    /**
     * 设置实例
     * @param $instance
     * @author chenqiaojie 2018-08-07
     */
    public function setInstance($instance)
    {
        $this->instance = $instance;
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
        $arr = get_object_vars($this->instance); //对象属性转数组
        $arr = array_filter($arr, function ($v, $k) {
            return !empty($v) || $v === 0;
        }, ARRAY_FILTER_USE_BOTH); //过滤掉为空的数组
        unset($arr['instance']);
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
        return $arr;
    }

    /**
     * 加载获得实例
     * @param bool $select 要获取查询字段
     * @return object|static|null
     * @author chenqiaojie 2018-05-16
     */
    public static function getInstance(bool $select = false)
    {
        $instance = new static();
        $instance->setInstance($instance);
        if ($select === true) {
            $classAttr = $instance->getClassAttr();
            foreach ($classAttr as $key => $value) {
                $instance->$key = $key;
            }
        }

        return $instance;
    }

    /**
     * 绑定数据
     * @param $data
     * @return object|static|null
     * @author chenqiaojie 2018-05-07
     */
    public static function bind($data)
    {
        $instance = new static();
        $instance->setInstance($instance);
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
     * 绑定表单数据
     * @param array $data
     * @return object|static|null
     * @author chenqiaojie 2018-05-13
     */
    public static function form(array $data)
    {
        $instance = new static();
        $instance->setInstance($instance);
        $fillable = $instance->getClassAttr();
        foreach ($fillable as $key => $value) {
            $func = 'set' . self::convertUnder($key);
            if (isset($data[$key])) {
                $instance->$func($data[$key]);
            }
        }
        return $instance;
    }
}