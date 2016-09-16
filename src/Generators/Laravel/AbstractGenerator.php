<?php

namespace Jeroenherczeg\Bee\Generators\Laravel;

/**
 * Class AbstractGenerator
 * @package Jeroenherczeg\Bee\Generators
 */
abstract class AbstractGenerator
{
    /**
     * @var
     */
    protected $data;

    /**
     * @var
     */
    protected $config;

    /**
     * @var
     */
    protected $output;

    /**
     * MigrationGenerator constructor.
     *
     * @param $data
     * @param $config
     */
    public function __construct($data, $config, $output)
    {
        $this->data = $data;
        $this->config = $config;
        $this->output = $output;
    }

    /**
     * @return mixed
     */
    abstract function generate();

    /**
     * @param $fileName
     *
     * @return mixed
     */
    protected function loadFile($fileName)
    {
        return file_get_contents(__DIR__ .'/../../../' . $fileName);
    }

    /**
     * @param $replacements
     * @param $stub
     *
     * @return mixed
     */
    protected function replace($replacements, $stub)
    {
        foreach ($replacements as $key => $value) {
            $stub = str_replace('{{' . $key . '}}', $value, $stub);
        }

        return $stub;
    }

    /**
     * @param $data
     * @param $fileName
     * @param $path
     */
    protected function saveFile($data, $fileName, $path)
    {
        $path = getcwd() . $path;

        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        file_put_contents($path . $fileName, $data);
    }
}