<?php

namespace Jeroenherczeg\Bee\Generators;

/**
 * Class RequestGenerator
 * @package Jeroenherczeg\Bee\Generators
 */
class RequestGenerator extends AbstractGenerator
{
    /**
     * Generate models
     */
    public function generate()
    {
        $stub = $this->loadFile($this->config->path->stub->request);

        foreach ($this->data->tables as $index => $table) {
            $replacements = [
                'namespace' => $this->config->default->namespace,
                'class' =>  ucfirst($table->name),
                'rules' => $this->buildRules($table)
            ];

            $contents = $this->replace($replacements, $stub);

            $fileName = ucfirst($table->name) . 'Request.php';
            $path = $this->config->path->output->requests;

            $this->saveFile($contents, $fileName, $path);

            $this->output->writeln('<info>Created request: ' . $fileName . '</info>');
        }
    }

    private function buildRules($table)
    {
        $rules = '';

        foreach ($table->columns as $column) {
            switch ($column->name) {
                case 'timestamps':
                    break;
                default:
                    $rules .= '\'' . $column->name . '\' => \'required\',';
            }

            $rules .= PHP_EOL . '            ';
        }
        
        return $rules;
    }
}
