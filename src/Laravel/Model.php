<?php


namespace Marstm\Laravel;


use Marstm\Container\Container;

trait Model
{
    public function save(array $options = [])
    {
        foreach ($this->toArr() as $att => $v) {
            $this->attributes[$att] = $v;
        }
        parent::save();
    }

    /**
     * Find a model by its primary key.
     * @param $id
     * @param string[] $columns
     * @return $this
     */
    public function find($id, $columns = ['*'])
    {
        $query = parent::find($id, $columns = ['*']);
        $arr = $query->toArray();
        $this->original = $query->toArray();
        $this->attributes = $arr;
        $this->exists = true;
        return $this->instance->bindData($query);
    }
}