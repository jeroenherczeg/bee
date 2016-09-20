<?php

namespace Jeroenherczeg\Bee\Generators;

use Illuminate\Filesystem\Filesystem;
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
     * @return array
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @return mixed
     */
    abstract protected function generate();

    /**
     * @return string
     */
    abstract protected function getStub();

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
     * @return string
     */
    protected function getStubPath()
    {
        return $this->config->getStubsDir() . $this->getStub();
    }
    
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

    /**
     * @param $result
     */
    protected function addResult($result)
    {
        $this->results[] = $result;
    }

    /**
     * @param $filename
     * @param $content
     */
    protected function storeFile($filename, $content)
    {
        $this->fs->put($this->getDestinationPath() . '/' . $filename, $content);

        $this->addResult($filename);
    }
}
