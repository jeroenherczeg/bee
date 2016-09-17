<?php

namespace Jeroenherczeg\Bee\Generators\Laravel;

use Illuminate\Support\Str;
use Jeroenherczeg\Bee\Generators\AbstractGenerator;

/**
 * Class TestGenerator
 * @package Jeroenherczeg\Bee\Generators
 */
class TestGenerator extends AbstractGenerator
{
    /**
     * Generate migrations
     */
    public function generate()
    {
        $this->createBaseTest();

        $str = new Str();
        $stub = $this->loadFile($this->config->path->stub->test);

        foreach ($this->data->tables as $index => $table) {
            $replacements = [
                'namespace' => $this->config->default->namespace,
                'class' => $this->makeClassName($table->name),
                'model' => strtolower($table->name),
                'models' => strtolower($str->plural($table->name)),
            ];

            $contents = $this->replace($replacements, $stub);

            $fileName = $this->makeClassName($table->name) . 'Test.php';
            $path = $this->config->path->output->tests;

            $this->saveFile($contents, $fileName, $path);

            $this->output->writeln('<info>Created test: ' . $fileName . '</info>');
        }
    }

    public function createBaseTest()
    {
        $stub = $this->loadFile($this->config->path->stub->basetest);

        $replacements = [
            'namespace' => $this->config->default->namespace,
        ];

        $contents = $this->replace($replacements, $stub);

        $fileName = 'TestCase.php';
        $path = $this->config->path->output->tests;

        $this->saveFile($contents, $fileName, $path);

        $this->output->writeln('<info>Created base test</info>');
    }
}