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
    protected $basesFilesDir;

    /**
     * @var
     */
    protected $stubsDir;

    /**
     * @var string
     */
    protected $namespace = 'App';

    /**
     * @var array
     */
    protected $tables = [];

    /**
     * @var string
     */
    protected $site_root = 'http://localhost:8080/';

    /**
     * @var string
     */
    protected $api_root = 'http://localhost:8080/api/';

    /**
     * Config constructor.
     */
    public function __construct()
    {
        $this->projectDir = getcwd();

        $this->baseFilesDir = __DIR__ . '/../../base_files/';

        $this->stubsDir = __DIR__ . '/../../stubs/';

        $fs = new Filesystem();
        $json = $fs->get($this->projectDir . '/.bee');

        if (!$this->isValidJson($json)) {
            throw new Exception('.bee is not a valid json file!');
        }

        $config = json_decode($json);

        if (isset($config->namespace)) {
            $this->namespace = $config->namespace;
        }

        if (isset($config->site_root)) {
            $this->site_root = $config->site_root;
        }

        if (isset($config->api_root)) {
            $this->api_root = $config->api_root;
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
    public function getBaseFilesDir()
    {
        return $this->baseFilesDir;
    }

    /**
     * @return string
     */
    public function getStubsDir()
    {
        return $this->stubsDir;
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

    /**
     * @return string
     */
    public function getSiteRoot()
    {
        return $this->site_root;
    }

    /**
     * @return string
     */
    public function getApiRoot()
    {
        return $this->api_root;
    }
}
