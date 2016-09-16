<?php

namespace Jeroenherczeg\Bee\Generators;

/**
 * Class ModelGenerator
 * @package Jeroenherczeg\Bee\Generators
 */
class ModelGenerator extends AbstractGenerator
{
    /**
     * Generate models
     */
    public function generate()
    {
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

            $this->output->writeln('<info>Created model: ' . $fileName . '</info>');
        }
    }

    private function buildFillableFields($table)
    {
        $fields = '';

        foreach ($table->columns as $column) {
            switch ($column->name) {
                case 'timestamps':
                    $fields .= '\'created_at\',' . PHP_EOL . '        ';
                    $fields .= '\'updated_at\',';
                    break;
                default:
                    $fields .= '\'' . $column->name . '\',';
            }

            $fields .= PHP_EOL . '        ';
        }

        return $fields;
    }
}