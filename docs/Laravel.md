

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
#### save()
```php

    /**
     * @param array $options
     */
    public function save(array $options = []){}

```

#### update()
```php

    /**
     * Update a record in the database.
     *
     * @param array $values
     * @return int
     */
    public function update(array $attributes = [], array $options = []){}

```

#### delete()
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