<?php

namespace marstm;


/**
 * Class Bean
 * @package PhpType
 */
class Bean
{
    protected static $instance;

    /**
     * 设置实例
     * @param $instance
     * @param $entityName
     */
    public static function setInstance($entityName)
    {
        self::$instance[$entityName] = new $entityName;
    }

    /**
     * 加载获得实例
     * @param bool $select 要获取查询字段
     * @return object|static|null
     * @author chenqiaojie 2018-05-16
     */
    public static function new(bool $select = false)
    {
        $class_name = get_called_class();
        if (isset(self::$instance[$class_name]) && self::$instance[$class_name] instanceof static) {
            return self::$instance[$class_name];
        }
        self::setInstance($class_name);
        $instance = self::$instance[$class_name];
        if ($select === true) {
            $classAttr = $instance->getClassAttr();
            foreach ($classAttr as $key => $value) {
                $instance->$key = $key;
            }
        }

        return self::$instance[$class_name];
    }

    /**
     * @param array $data
     * @param null $abstract
     * @return object|static|null
     */
    public static function bind($data = [], $abstract = null)
    {
        if (is_null($abstract)) {
            $abstract = get_called_class();
        }
        if (!isset(self::$instance[$abstract])) {
            self::setInstance($abstract);
            $instance = self::$instance[$abstract];
        } else {
            $instance = self::getAlias($abstract);
        }
        $classAttr = $instance->getClassAttr($abstract);
        foreach ($classAttr as $key => $value) {
            $func = 'set' . $instance::convertUnder($key);
            if (isset($data[$key])) {
                $instance->$func($data[$key]);
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
        $abstract = get_called_class();
        $arr = get_object_vars(self::$instance[$abstract]); //对象属性转数组
        $arr = array_filter($arr, function ($v, $k) {
            return !empty($v);
        }, ARRAY_FILTER_USE_BOTH); //过滤掉为空的数组
        return $arr;
    }

    /**
     * Get the alias for an abstract if available.
     *
     * @param string $abstract
     * @return string
     *
     * @throws \LogicException
     */
    public static function getAlias($abstract)
    {
        if (!isset(self::$instance[$abstract])) {
            return self::$instance[$abstract];
        }
        if (is_string($abstract) && isset(self::$instance[$abstract])) {
            return self::$instance[$abstract];
        }
        return null;
    }

    /**
     * 获取对象属性
     * @return array
     * @author chenqiaojie 2018-08-07
     */
    private function getClassAttr($abstract)
    {
        $arr = get_object_vars(self::$instance[$abstract]); //对象属性转数组
        return $arr;
    }


    public function build($concrete)
    {
        $reflector = new \ReflectionClass($concrete);

        // If the type is not instantiable, the developer is attempting to resolve
        // an abstract type such as an Interface of Abstract Class and there is
        // no binding registered for the abstractions so we need to bail out.
        if (!$reflector->isInstantiable()) {
            return $this->notInstantiable($concrete);
        }

        $this->buildStack[] = $concrete;

        $constructor = $reflector->getConstructor();
        if (is_null($constructor)) {
            array_pop($this->buildStack);

            return new $concrete;
        }

        $dependencies = $constructor->getParameters();
    }

}