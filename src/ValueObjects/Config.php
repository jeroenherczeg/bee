<?php

namespace Jeroenherczeg\Bee\ValueObjects;

use Exception;
use Illuminate\Filesystem\Filesystem;

/**
 * Class Config
 * @package Jeroenherczeg\Bee\ValueObject
 */
class Config
{
    /**
     * @var
     */
    protected $projectDir;

    /**
     * @var
     */
    protected $baseFileDir;

    /**
     * @var string
     */
    protected $namespace = 'App';

    /**
     * @var array
     */
    protected $tables = [];

    /**
     * Config constructor.
     */
    public function __construct()
    {
        $this->projectDir = getcwd();

        $this->baseFileDir = __DIR__ . '/../../';

        $fs = new Filesystem();
        $config = $fs->get($this->projectDir . '/.bee');

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

    /**
     * @param $contents
     *
     * @return bool
     */
    protected function isValidJson($contents) {
        json_decode($contents);

        return (json_last_error() === JSON_ERROR_NONE);
    }

    /**
     * @return string
     */
    public function getProjectDir()
    {
        return $this->projectDir;
    }

    /**
     * @return string
     */
    public function getBaseFileDir()
    {
        return $this->baseFileDir;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return array
     */
    public function getTables()
    {
        return $this->tables;
    }
}