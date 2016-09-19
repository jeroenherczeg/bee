<?php

namespace Jeroenherczeg\Bee\ValueObject;

use Exception;
use Illuminate\Filesystem\Filesystem;

class Config
{
    protected $namespace = 'App';

    protected $tables = [];

    public function __construct()
    {
        $fs = new Filesystem();
        $config = $this->fs->get(getcwd() . '/.bee');

        if (!$this->isValidJson($config)) {
            throw new Exception('.bee is not a valid json file!');
        }

        if (isset($config->namespace)) {
            $this->namespace = $config->namespace;
        }

        if (isset($config->tables)) {
            $this->tables = $config->tables;
        }
    }

    protected function isValidJson($contents) {
        json_decode($contents);

        return (json_last_error() === JSON_ERROR_NONE);
    }

    public function getNamespace()
    {
        return $this->namespace;
    }

    public function getTables()
    {
        return $this->tables;
    }
}