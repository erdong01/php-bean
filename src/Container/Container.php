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
        if (is_object($entity)) {
            self::$globalInstance[get_class($entity)] = $entity;
            return;
        }
        if (is_string($entity)) {
            self::$globalInstance[$entity] = new $entity;
        }
        return;
    }

    /**
     * 清空实例
     */
    public function clearInstance()
    {
        $this->setInstance($this->getAlias(get_class($this->instance)));
    }

    /**
     * @param object|static $entity
     */
    public function setInstance($entity)
    {

        if (is_string($entity)) {
            $this->instance = new $entity;
        }
        if (is_object($entity)) {
            $this->instance = $entity;
        }
        $this->setGlobalInstance($this->instance);
    }

    /**
     * 获取实例
     * @return object|static
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

    /**
     * @param $e
     * @param null $type
     * @return array|array[]|mixed|void
     */
    private function objectArray($e, $type = null)
    {
        $arr = [];
        if (is_object($e)) {
            if ($this->isBean($e)) {
                $arr = $this->beanToArr($e);
            } else if (is_object($e)) {
                $arr = get_object_vars($e);
            }
            if ($type == 3) {
                $this->items[] = $arr;
                return;
            }
            return $arr;
        }
        $result = [];
        foreach ($e as $k => $v) {
            if ($this->isBean($v)) {
                $arr = $this->beanToArr($v);
            } else if (is_object($v)) {
                $arr = get_object_vars($v);
            } else {
                $arr = $v;
            }
            if ($type === 1) {
                array_unshift($this->items, $arr);
            } else if ($type === 3) {
                $this->items[$k] = $arr;
            } else if ($type === 2) {
                array_splice($this->items, $this->index, 0, $arr);
            } else {
                $result[$k] = $arr;
            }
        }
        if ($type !== null) {
            return;
        }
        return $result;
    }

    public function isBean($object)
    {
        if (is_object($object) && method_exists($object, 'toArr')) {
            return true;
        }
        return false;
    }

    public function beanToArr($object)
    {
        return $object->toArr();
    }
}
