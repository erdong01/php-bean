<?php


namespace Marstm\Laravel;


trait Model
{

    public function save(array $options = [])
    {
        $this->attributes = $this->toArr();
        parent::save();
    }
}