<?php

namespace Marstm\Support\Traits;

use Marstm\Container\Container;
use Marstm\Laravel\Model;

/**
 * Class Bean
 * @package PhpType
 */
trait Bean
{
    use Container, Model;

    /**
     * @param mixed ...$columns
     * @return object|static|null
     */
    public static function new(...$columns)
    {
        $instance = new self(...$columns);
        $instance->instance = $instance;
        $instance->setProperties();
        return $instance;
    }

    /**
     * @return \Marstm\ArrayList|object|static
     */
    public static function ArrayList()
    {
        $aL = arrayList();
        $aL->setInstance(self::new());
        return $aL;
    }

    /**
     * @return mixed
     */
    public function setProperties()
    {
        if ($this->instance->properties) {
            return $this->instance->properties;
        }
        $class = new \ReflectionObject($this->instance);
        $className = $class->getName();
        $properties = $class->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PRIVATE);
        $nameArr = [];
        foreach ($properties as $propertiesV) {
            if ($propertiesV->class === $className) {
                $nameArr[] = $propertiesV->name;
            }
        }
        $this->instance->properties = $nameArr;
    }

    /**
     * Binding data
     * @param array $data
     * @return object|static
     */
    public static function bind($data = [])
    {
        $instance = new self();
        $instance->instance = $instance;
        $instance->setProperties();
        $instance->bindData($data);
        return $instance;
    }

    /**
     * @param array $data
     * @return object|static
     */
    public function bindData($data = [])
    {
        $instance = $this->instance;
        $classAttr = $this->instance->properties;
        foreach ($classAttr as $key) {
            $func = 'set' . $instance::convertUnder($key);
            if (!method_exists($instance, $func)
                || !property_exists($instance, $key)) {
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
            if (!method_exists($this->instance, $func)
                || !property_exists($this->instance, $v)
                || !isset($this->{$v})) {
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

    /**
     * 获取实例
     * @return object|static
     */
    public function bean()
    {
        return $this->instance;
    }
}
