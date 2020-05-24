<?php


namespace Mars;


trait App
{
    private static $instanceArr;

    /**
     * 设置实例
     * @param $instance
     * @param $entityName
     */
    public function setInstance($entityName)
    {
        //        if (isset(self::$instance[$class_name]) && self::$instance[$class_name] instanceof static) {
//            return self::$instance[$class_name];
//        }
        // $class_name = get_called_class();
        self::$instanceArr[$entityName] = new $entityName;

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
        if (!isset(self::$instanceArr[$abstract])) {
            return self::$instanceArr[$abstract];
        }
        if (is_string($abstract) && isset(self::$instanceArr[$abstract])) {
            return self::$instanceArr[$abstract];
        }
        return null;
    }
}