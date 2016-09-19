<?php

namespace Jeroenherczeg\Bee\Generators;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Jeroenherczeg\Bee\ValueObjects\Config;

/**
 * Class AbstractGenerator
 * @package Jeroenherczeg\Bee\Generators
 */
abstract class AbstractGenerator
{
    /**
     * @var
     */
    protected $config;

    /**
     * @var Filesystem
     */
    protected $fs;

    /**
     * @var array
     */
    protected $results = [];

    /**
     * AbstractGenerator constructor.
     *
     * @param $table
     * @param $config
     */
    public function __construct()
    {
        $this->config = new Config();
        $this->fs = new Filesystem();
    }

    /**
     * @return mixed
     */
    abstract protected function generate();

    /**
     * @return string
     */
    abstract protected function getStubPath();

    /**
     * @return string
     */
    abstract protected function getDestinationPath();

    /**
     * @return array
     */
    abstract protected function getReplacements($table);

    /**
     * @return string
     */
    abstract protected function getFilenameFormat();
    

    /**
     * @param $replacements
     * @param $stub
     *
     * @return string
     */
    protected function replace($replacements, $stub)
    {
        foreach ($replacements as $key => $value) {
            $stub = str_replace('{{' . $key . '}}', $value, $stub);
        }

        return $stub;
    }

    protected function addResult($result)
    {
        $this->results[] = $result;
    }

    protected function storeFile($filename, $content)
    {
        $this->fs->put($this->getDestinationPath() . '/' . $filename, $content);

        $this->addResult($filename);
    }
}
