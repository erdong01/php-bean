<?php

namespace Marstm\Container;


trait Container
{
    /**
     * @var array
     */
    protected $items = [];

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
     * @var string
     */
    protected $prefix;

    /**
     * @var array
     */
    protected $beanList = [];


    /**
     * 设置实例
     * @param $instance
     * @param $entityName
     */
    public function setGlobalInstance($entity)
    {
        if (is_string($entity)) {
            self::$globalInstance[$entity] = new $entity;
        }
        if (is_object($entity)) {
            self::$globalInstance[get_class($entity)] = $entity;
        }
    }

    /**
     * 清空实例
     */
    public function clearInstance()
    {
        $this->setInstance($this->getAlias(get_class($this->instance)));
    }

    /**
     * 设置实例
     * @param $instance
     * @param $entityName
     */
    public function setInstance($entity)
    {

        if (is_string($entity)) {
            $this->instance = new $entity;
        }
        if (is_object($entity)) {
            $this->instance = $entity;
        }
    }

    /**
     * 获取实例
     * @param $instance
     * @param $entityName
     */
    public function getInstance()
    {
        return $this->instance;
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
     * @param null $index
     * @return bool
     */
    public function isEmpty($index = null)
    {
        if ($index) {
            if (isset($this->items[$index]) && empty($this->items[$index])) {
                return true;
            }
        }
        return empty($this->items);
    }
}
