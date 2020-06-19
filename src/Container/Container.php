<?php


namespace Marstm\Container;


use Marstm\Support\I\Arrayable;

trait Container
{
    /**
     * @var array  The current globally available container
     */
    protected static $globalInstance;

    /**
     * @var object
     */
    protected $instance;

    /**
     * @var array
     */
    protected $properties;

    /**
     * @var array
     */
    protected $beanList = [];

    /**
     * @var array
     */
    protected $items = [];

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
//        $class_name = get_called_class();
        self::$globalInstance[$entityName] = new $entityName;

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
        if (!isset(self::$globalInstance[$abstract])) {
            return self::$globalInstance[$abstract];
        }
        if (is_string($abstract) && isset(self::$globalInstance[$abstract])) {
            return self::$globalInstance[$abstract];
        }
        return null;
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param array $items
     */
    public function setItems(array $items): void
    {
        $this->items = $items;
    }

    /**
     * Get the collection of items as a plain array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_map(function ($value) {
            return $value instanceof Arrayable ? $value->toArray() : $value;
        }, $this->items);
    }

}