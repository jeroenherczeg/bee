<?php

namespace Jeroenherczeg\Bee\ValueObject;

class Config
{
    protected $namespace = 'App';

    protected $tables = [];

    public function getNamespace()
    {
        return $this->namespace;
    }

    public function getTables()
    {
        return $this->tables;
    }
}