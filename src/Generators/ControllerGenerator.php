<?php

namespace Jeroenherczeg\Bee\Generators;

use Illuminate\Support\Str;

/**
 * Class ControllerGenerator
 * @package Jeroenherczeg\Bee\Generators
 */
class ControllerGenerator extends AbstractGenerator
{
    /**
     * Generate controllers
     */
    public function generate()
    {
        $str = new Str();
        $stub = $this->loadFile($this->config->path->stub->controller);

        foreach ($this->data->tables as $index => $table) {
            $replacements = [
                'namespace' => $this->config->default->namespace,
                'class' =>  ucfirst($table->name),
                'models' => strtolower($str->plural($table->name)),
                'model' => strtolower($table->name),
            ];

            $contents = $this->replace($replacements, $stub);

            $fileName = ucfirst($table->name) . 'Controller.php';
            $path = $this->config->path->output->controllers;

            $this->saveFile($contents, $fileName, $path);

            $this->output->writeln('<info>Created controller: ' . $fileName . '</info>');
        }
    }
}