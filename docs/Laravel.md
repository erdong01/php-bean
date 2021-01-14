

新建的php bean文件  基础laravel Model文件
```php
/**
 * 考试
 * Class BExam
 * @package App\Repositories\Entitys
 */
class BExam extends Exam
{
    use Bean;
    private int|null $exam_no;
     /**
     * @return int|null
     */
    public function getExamNo(): ?int
    {
        return $this->exam_no;
    }

    /**
     * @param int|null $exam_no
     */
    public function setExamNo(?int $exam_no): void
    {
        $this->exam_no = $exam_no;
    }
 }

```

#### find()
```php
    /**
     * Find a model by its primary key.
     * @param $id
     * @param string[] $columns
     * @return $this
     */
    public function find($id, $columns = ['*']){}
    
```
#### where()
```php
    /**
     * Add a basic where clause to the query.
     *
     * @param \Closure|string|array $column
     * @param mixed $operator
     * @param mixed $value
     * @param string $boolean
     * @return $this
     */
    public function where($column, $operator = null, $value = null, $boolean = 'and'){}
    
```
#### orWhere()
```php
    /**
     * Add an "or where" clause to the query.
     *
     * @param \Closure|string|array $column
     * @param mixed $operator
     * @param mixed $value
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function orWhere($column, $operator = null, $value = null){}
```
#### whereIn()
```php
    /**
     * Add a "where in" clause to the query.
     *
     * @param string $column
     * @param mixed $values
     * @param string $boolean
     * @param bool $not
     * @return $this
     */
    public function whereIn($column, $values, $boolean = 'and', $not = false){}

```
#### whereNotIn()
```php
    /**
     * Add a "where not in" clause to the query.
     *
     * @param string $column
     * @param mixed $values
     * @param string $boolean
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function whereNotIn($column, $values, $boolean = 'and'){}

```
#### whereNull()
```php
    /**
     * Add a "where null" clause to the query.
     *
     * @param string|array $columns
     * @param string $boolean
     * @param bool $not
     * @return \Illuminate\Database\Query\Builder
     */
    public function whereNull($columns, $boolean = 'and', $not = false){}
```
#### whereNotNull()
```php
    /**
     * Add a "where not null" clause to the query.
     *
     * @param string|array $columns
     * @param string $boolean
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function whereNotNull($columns, $boolean = 'and'){}
```

#### whereBetween()
```php
    /**
     * Add a where between statement to the query.
     *
     * @param string $column
     * @param array $values
     * @param string $boolean
     * @param bool $not
     * @return \Illuminate\Database\Query\Builder
     */
    public function whereBetween($column, array $values, $boolean = 'and', $not = false){}
```
#### whereNotBetween()
```php
     /**
     * Add a where not between statement to the query.
     *
     * @param string $column
     * @param array $values
     * @param string $boolean
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function whereNotBetween($column, array $values, $boolean = 'and'){}
```

#### whereColumn()
```php
    /**
     * Add a "where" clause comparing two columns to the query.
     *
     * @param string|array $first
     * @param string|null $operator
     * @param string|null $second
     * @param string|null $boolean
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function whereColumn($first, $operator = null, $second = null, $boolean = 'and'){}
```

#### whereExists()
```php
       /**
     * Add an exists clause to the query.
     *
     * @param \Closure $callback
     * @param string $boolean
     * @param bool $not
     * @return $this
     */
    public function whereExists(Closure $callback, $boolean = 'and', $not = false){}
```
#### 更新
```php

    /**
     * @param array $options
     */
    public function save(array $options = []){}

```

#### 批量更新
```php

    /**
     * Update a record in the database.
     *
     * @param array $values
     * @return int
     */
    public function update(array $attributes = [], array $options = []){}

```

#### 删除模型
```php

    /**
     * Delete the model from the database.
     *
     * @return bool|null
     *
     * @throws \Exception
     */
    public function delete(){}

```

#### history()
```php

    public function history(){}

```


#### 永久删除
```php
    /**
     * Run the default delete function on the builder.
     *
     * Since we do not apply scopes here, the row will actually be deleted.
     *
     * @return mixed
     */
    public function forceDelete(){}

```


#### 恢复软删除模型#
```php
    public function restore(){}

```