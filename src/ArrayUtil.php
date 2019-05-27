<?php

namespace PhpType;

/**
 * 数组工具
 * Class ArrayUtil
 * @package App\Utils
 */
class ArrayUtil
{
    protected $instance;

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
     * 加载获得实例
     * @param bool $select 要获取查询字段
     * @return object|static|null
     * @author chenqiaojie 2018-05-16
     */
    public static function new()
    {
        $instance = new static();
        $instance->setInstance($instance);
        return $instance;
    }

    /**
     * 把两个字段转换成 关系型一维数组
     * @param $array
     * @param string $key
     * @param string $value
     * @return array
     * @author chenqiaojie 2018-12-21
     */
    public function arrayKeyValue($array, string $key, string $value)
    {
        $arr = [];
        foreach ($array as $k => $v) {
            $arr[$v[$key]] = $v[$value];
        }
        return $arr;
    }

    /**
     * 把二维数组中一个值当做键名 转成新数组
     * 需要数据唯一性的
     * @param $array
     * @param string $key
     * @return array
     * @author chenqiaojie 2018-12-21
     */
    public function keyArray($array, $key)
    {
        $arr = [];
        foreach ($array as $k => $v) {
            $arr[$v[$key]] = $v;
        }
        return $arr;
    }

    /**
     * 把二维数组中一个值当做键名 转成新三维数组
     * 不需要数据唯一性的
     * @param $array
     * @param $key
     * @return array
     * @author chenqiaojie 2019-01-11
     */
    public function arrayKeyArray($array, $key): array
    {
        $arr = [];
        foreach ($array as $k => $v) {
            $arr[$v[$key]][] = $v;
        }
        return $arr;
    }


    /**
     * 二维数组根据某个字段排序
     * @param array $array 要排序的数组
     * @param string $keys 要排序的键字段
     * @param int $sort 排序类型  SORT_ASC     SORT_DESC
     * @return array 排序后的数组
     */
    public function twoArraySort($array, $keys, $sort = SORT_DESC): array
    {
        $keysValue = [];
        foreach ($array as $k => $v) {
            $keysValue[$k] = $v[$keys];
        }
        array_multisort($keysValue, $sort, $array);
        return $array;
    }
}
