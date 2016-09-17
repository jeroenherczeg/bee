<?php

namespace Jeroenherczeg\Bee\Generators\Laravel;
use Jeroenherczeg\Bee\Generators\AbstractGenerator;

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
        $stubUser = $this->loadFile($this->config->path->stub->user);

        foreach ($this->data->tables as $index => $table) {
            $replacements = [
                'namespace' => $this->config->default->namespace,
                'class' =>  $this->makeClassName($table->name),
                'fillable_fields' => $this->buildFillableFields($table),
                'hidden_fields' => $this->buildHiddenFields($table),
                'accessors' => $this->buildAccessors($table),
            ];

            if (strtolower($table->name) == 'user') {
                $contents = $this->replace($replacements, $stubUser);
            } else {
                $contents = $this->replace($replacements, $stub);
            }

            $fileName = $this->makeClassName($table->name) . '.php';
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

    private function buildHiddenFields($table)
    {
        $fields = '';

        //foreach ($table->columns as $column) {
        //    if ($column->name == 'password' || $column->name == 'remember_token') {
        //        $fields .= '\'' . $column->name . '\',';
        //        $fields .= PHP_EOL . '        ';
        //    }
        //}

        return $fields;
    }

    private function buildAccessors($table)
    {
        $fields = '';

        foreach ($table->columns as $column) {
            if ($column->name == 'password') {
                $fields .= 'public function setPasswordAttribute($value)' . PHP_EOL;
                $fields .= '{' . PHP_EOL;
                $fields .= '    if ($value) {' . PHP_EOL;
                $fields .= '        $this->attributes[\'password\'] = bcrypt($value);' . PHP_EOL;
                $fields .= '    }' . PHP_EOL;
                $fields .= '}' . PHP_EOL;
            }
        }

        return $fields;
    }
}