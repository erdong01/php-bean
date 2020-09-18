<?php

namespace Marstm\Support\Traits;

use Marstm\Container\Container;

/**
 * Class Bean
 * @package PhpType
 */
trait Bean
{
    use Container;

    /**
     * @param mixed ...$columns
     * @return object|static|null
     */
    public static function new(...$columns)
    {
        $instance = new self(...$columns);
        $instance->instance = $instance;
        $instance->getProperties();
        return $instance;
    }

    /**
     * @return mixed
     */
    public function getProperties()
    {
        if ($this->instance->properties) {
            return $this->instance->properties;
        }
        $class = new \ReflectionObject($this->instance);
        $properties = $class->getProperties(\ReflectionProperty::IS_PRIVATE);
        $this->instance->properties = array_column($properties, 'name');
    }

    /**
     * Binding data
     * @param array $data
     * @return object|static
     */
    public static function bind($data = [])
    {
        $instance = new static();
        $instance->instance = $instance;
        $classAttr = $instance->instance->properties;
        foreach ($classAttr as $key) {
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
     * Convert underline naming to camel case naming
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
     * Convert to array
     * @return array
     */
    public function toArr()
    {
        $arr = [];  //对象属性转数组
        $classAttr = $this->properties;

        foreach ($classAttr as $v) {
            $func = 'get' . $this->convertUnder($v);
            if (!method_exists($this->instance, $func) || !property_exists($this->instance, $v) || !$this->{$v}) {
                continue;
            }
            $val = $this->$func();
            if ($val !== null) {
                $arr[$v] = $val;
            }
        }
        return $arr;
    }

    /**
     * Get object properties
     * @return array
     */
    private function getClassAttr()
    {
        $arr = get_object_vars($this->instance); //对象属性转数组
        $this->unset($arr);
        return $arr;
    }

    /**
     * Release attribute
     * @param $arr
     */
    private function unset(&$arr)
    {
        unset($arr['instance']);
        unset($arr['instanceArr']);
        unset($arr['properties']);
    }
}