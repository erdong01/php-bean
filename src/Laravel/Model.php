<?php

namespace Marstm\Laravel;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Marstm\Container\Container;

trait Model
{
    use Container;

    /**
     * @var \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder
     */
    protected $model;

    /**
     * Execute the query and get the first result.
     *
     * @param array|string $columns
     * @return \Illuminate\Database\Eloquent\Model|object|static|null
     */
    public function first($columns = ['*'])
    {
        dd($this->getModel()->getQuery());
        $query = $this->getModel()->getQuery()->onceWithColumns(Arr::wrap($columns), function () {
            return $this->processor->processSelect($this, $this->runSelect());
        });
        dd($query);
    }

    /**
     * Set the columns to be selected.
     *
     * @param array|mixed $columns
     * @return $this
     */
    public function select($columns = ['*'])
    {
        $this->getModel()->select($columns);
        return $this;
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
        $this->original = $arr;
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
     * @return bool
     */
    public function save(array $options = [])
    {
        if ($this->properties) {
            foreach ($this->toArr() as $att => $v) {
                $this->attributes[$att] = $v;
            }
        }
        return parent::save();
    }

    /**
     * Save a new model and return the instance.
     *
     * @param array $attributes
     * @return $this|null
     */
    public function create(array $attributes = [])
    {
        foreach ($this->toArr() as $att => $v) {
            $this->attributes[$att] = $v;
        }
        $attributes = array_merge($attributes, $this->attributes);
        $query = $this->getModel()->create($attributes);
        $arr = $query->toArray();
        $this->original = $arr;
        $this->attributes = $arr;
        $this->exists = true;
        return $this->instance->bindData($arr);
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
