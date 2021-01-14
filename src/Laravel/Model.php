<?php

namespace Marstm\Laravel;

use Closure;
use Marstm\Support\Traits\Arr;

trait Model
{

    /**
     * @var \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder
     */
    protected $model;

    /**
     * Execute the query and get the first result.
     *
     * @param array|string $columns
     * @return $this;
     */
    public function first($columns = ['*'])
    {
        $query = $this->onceWithColumns(\Marstm\Arr::wrap($columns));
        return $this->bindData(arrayList($query)->first());
    }


    /**
     * @param string[] $columns
     * @return \Marstm\ArrayList
     */
    public function get($columns = ['*'])
    {
        $query = $this->onceWithColumns(Arr::wrap($columns));
        $aL = arrayList($query);
        $aL->setInstance($this);
        return $aL;
    }

    /**
     * Execute the given callback while selecting the given columns.
     *
     * After running the callback, the columns are reset to the original value.
     *
     * @param array $columns
     * @param callable $callback
     * @return mixed
     */
    protected function onceWithColumns($columns)
    {
        $query = $this->getModel()->getQuery();
        $original = $query->columns;
        if (is_null($original)) {
            $query->columns = $columns;
            $this->getModel()->setQuery($query);
        }
        $select = $query->connection->select(
            $query->toSql(), $query->getBindings(), !$query->useWritePdo
        );
        $result = $query->processor->processSelect($query, $select);

        $query->columns = $original;
        $this->getModel()->setQuery($query);
        return $result;
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
     * Add an "or where" clause to the query.
     *
     * @param \Closure|string|array $column
     * @param mixed $operator
     * @param mixed $value
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function orWhere($column, $operator = null, $value = null)
    {
        $this->model = $this->getModel()->orWhere($column, $operator, $value);
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
     * Add a "where not in" clause to the query.
     *
     * @param string $column
     * @param mixed $values
     * @param string $boolean
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function whereNotIn($column, $values, $boolean = 'and')
    {
        $this->model = $this->getModel()->whereNotIn($column, $values, $boolean);
        return $this;
    }

    /**
     * Add a "where null" clause to the query.
     *
     * @param string|array $columns
     * @param string $boolean
     * @param bool $not
     * @return \Illuminate\Database\Query\Builder
     */
    public function whereNull($columns, $boolean = 'and', $not = false)
    {
        $this->model = $this->getModel()->whereNull($columns, $boolean, $not);
        return $this;
    }

    /**
     * Add a "where not null" clause to the query.
     *
     * @param string|array $columns
     * @param string $boolean
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function whereNotNull($columns, $boolean = 'and')
    {
        $this->model = $this->getModel()->whereNotNull($columns, $boolea);
        return $this;
    }

    /**
     * Add a where between statement to the query.
     *
     * @param string $column
     * @param array $values
     * @param string $boolean
     * @param bool $not
     * @return \Illuminate\Database\Query\Builder
     */
    public function whereBetween($column, array $values, $boolean = 'and', $not = false)
    {
        $this->model = $this->getModel()->whereBetween($column, $values, $boolean, $not);
        return $this;
    }

    /**
     * Add a where not between statement to the query.
     *
     * @param string $column
     * @param array $values
     * @param string $boolean
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function whereNotBetween($column, array $values, $boolean = 'and')
    {
        $this->model = $this->getModel()->whereNotBetween($column, $values, $boolean);
        return $this;
    }

    /**
     * Add a "where date" statement to the query.
     *
     * @param string $column
     * @param string $operator
     * @param \DateTimeInterface|string|null $value
     * @param string $boolean
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function whereDate($column, $operator, $value = null, $boolean = 'and')
    {
        $this->model = $this->getModel()->whereDate($column, $operator, $value, $boolean);
        return $this;
    }

    /**
     * Add a "where month" statement to the query.
     *
     * @param string $column
     * @param string $operator
     * @param \DateTimeInterface|string|null $value
     * @param string $boolean
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function whereMonth($column, $operator, $value = null, $boolean = 'and')
    {
        $this->model = $this->getModel()->whereMonth($column, $operator, $value, $boolean);
        return $this;
    }

    /**
     * Add a "where day" statement to the query.
     *
     * @param string $column
     * @param string $operator
     * @param \DateTimeInterface|string|null $value
     * @param string $boolean
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function whereDay($column, $operator, $value = null, $boolean = 'and')
    {
        $this->model = $this->getModel()->whereMonth($column, $operator, $value, $boolean);
        return $this;
    }

    /**
     * Add a "where time" statement to the query.
     *
     * @param string $column
     * @param string $operator
     * @param \DateTimeInterface|string|null $value
     * @param string $boolean
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function whereTime($column, $operator, $value = null, $boolean = 'and')
    {
        $this->model = $this->getModel()->whereTime($column, $operator, $value, $boolean);
        return $this;
    }

    /**
     * Add a "where" clause comparing two columns to the query.
     *
     * @param string|array $first
     * @param string|null $operator
     * @param string|null $second
     * @param string|null $boolean
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function whereColumn($first, $operator = null, $second = null, $boolean = 'and')
    {
        $this->model = $this->getModel()->whereColumn($first, $operator, $second, $boolean);
        return $this;
    }

    /**
     * Add an exists clause to the query.
     *
     * @param \Closure $callback
     * @param string $boolean
     * @param bool $not
     * @return $this
     */
    public function whereExists(Closure $callback, $boolean = 'and', $not = false)
    {
        $this->model = $this->getModel()->whereExists($callback, $boolean, $not);
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

    public function history()
    {
        $this->model = $this->getModel()->history();
        return $this;
    }

    /**
     * Run the default delete function on the builder.
     *
     * Since we do not apply scopes here, the row will actually be deleted.
     *
     * @return mixed
     */
    public function forceDelete()
    {
        return $this->getModel()->forceDelete();
    }

    public function restore()
    {
        return $this->getModel()->restore();
    }

}
