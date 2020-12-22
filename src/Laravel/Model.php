<?php

namespace Marstm\Laravel;

trait Model
{
    /**
     * @var \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder
     */
    protected $model;

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

    /**
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|Model
     * @author chenqiaojie 2020-12-21
     */
    public function getModel()
    {
        if (!$this->model) {
            $this->model = $this->instance->query();
        }
        return $this->model;
    }

    /**
     * Add a basic where clause to the query.
     *
     * @param \Closure|string|array $column
     * @param mixed $operator
     * @param mixed $value
     * @param string $boolean
     * @return $this
     */
    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        $this->model = $this->getModel()->where($column, $operator, $value, $boolean);
        return $this;
    }

    /**
     * Add a "where in" clause to the query.
     *
     * @param string $column
     * @param mixed $values
     * @param string $boolean
     * @param bool $not
     * @return $this
     */
    public function whereIn($column, $values, $boolean = 'and', $not = false)
    {
        $this->model = $this->getModel()->whereIn($column, $values, $boolean, $not);
        return $this;
    }

    /**
     * @param array $options
     */
    public function save(array $options = [])
    {
        foreach ($this->toArr() as $att => $v) {
            $this->attributes[$att] = $v;
        }
        parent::save();
    }

    /**
     * Update a record in the database.
     *
     * @param array $values
     * @return int
     */
    public function update(array $attributes = [], array $options = [])
    {
        $arr = $this->toArr();
        if (!empty($values)) {
            $arr = array_merge($arr, $values);
        }
        return $this->getModel()->update($arr);
    }

    /**
     * Delete the model from the database.
     *
     * @return bool|null
     *
     * @throws \Exception
     */
    public function delete()
    {
        foreach ($this->toArr() as $att => $v) {
            $this->where($att, $v);
        }
        return $this->getModel()->delete();
    }
}
