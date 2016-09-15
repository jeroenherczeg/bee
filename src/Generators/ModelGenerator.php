<?php

namespace Jeroenherczeg\Bee\Generators;
use Illuminate\Support\Str;

/**
 * Class MigrationGenerator
 * @package Jeroenherczeg\Bee\Generators
 */
class ModelGenerator extends AbstractGenerator
{
    /**
     * Generate a migration
     */
    public function generate()
    {
        $str = new Str();
        $stub = $this->loadFile($this->config->path->stub->model);

        foreach ($this->data->tables as $index => $table) {
            $replacements = [
                'namespace' => $this->config->default->namespace,
                'class' =>  ucfirst($table->name),
                'fillable_fields' => $this->buildFillableFields($table)
            ];

            $contents = $this->replace($replacements, $stub);

            $fileName = ucfirst($table->name) . '.php';
            $path = $this->config->path->output->models;

            $this->saveFile($contents, $fileName, $path);

            $this->output->writeln('<info>Creating model: ' . $fileName . '</info>');
        }
    }

    private function buildFillableFields($table)
    {
        $fields = '';

        foreach ($table->columns as $column) {
            $fields .= '\'' . $column->name . '\'' . PHP_EOL . '        ';
        }

        return $fields;
    }
}