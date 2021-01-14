<?php


namespace Marstm\Laravel\ArrayList;

use Marstm\Container\Container;

trait ArrayListModel
{

    /**
     * Insert a new record into the database.
     *
     * @param array $values
     * @return bool
     */
    public function insert()
    {
        $this->bean()->query()->insert($this->getItems());
    }
}