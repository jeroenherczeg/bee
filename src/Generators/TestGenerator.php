<?php

namespace Jeroenherczeg\Bee\Generators;

use Illuminate\Support\Str;

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
        $stub = $this->loadFile($this->config->path->stub->test);

        foreach ($this->data->tables as $index => $table) {
            $replacements = [
                'namespace' => $this->config->default->namespace,
                'class' => ucfirst($table->name),
                'model' => strtolower($table->name),
            ];

            $contents = $this->replace($replacements, $stub);

            $fileName = ucfirst($table->name) . 'Test.php';
            $path = $this->config->path->output->tests;

            $this->saveFile($contents, $fileName, $path);

            $this->output->writeln('<info>Created test: ' . $fileName . '</info>');
        }
    }
}