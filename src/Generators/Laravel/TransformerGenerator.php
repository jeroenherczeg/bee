<?php

namespace Jeroenherczeg\Bee\Generators\Laravel;
use Jeroenherczeg\Bee\Generators\AbstractGenerator;

/**
 * Class TransformerGenerator
 * @package Jeroenherczeg\Bee\Generators
 */
class TransformerGenerator extends AbstractGenerator
{
    /**
     * Generate models
     */
    public function generate()
    {
        $stub = $this->loadFile($this->config->path->stub->transformer);

        foreach ($this->data->tables as $index => $table) {
            $replacements = [
                'namespace' => $this->config->default->namespace,
                'class' =>  ucfirst($table->name),
                'model' =>  strtolower($table->name),
                'fields' => $this->buildFields($table)
            ];

            $contents = $this->replace($replacements, $stub);

            $fileName = ucfirst($table->name) . 'Transformer.php';
            $path = $this->config->path->output->transformers;

            $this->saveFile($contents, $fileName, $path);

            $this->output->writeln('<info>Created transformer: ' . $fileName . '</info>');
        }
    }

    private function buildFields($table)
    {
        $fields = '';

        foreach ($table->columns as $column) {
            switch ($column->name) {
                case 'timestamps':
                    $fields .= '\'created_at\' => $' . strtolower($table->name) . '->created_at,' . PHP_EOL . '            ';
                    $fields .= '\'updated_at\' => $' . strtolower($table->name) . '->updated_at,';
                    break;
                default:
                    $fields .= '\'' . $column->name . '\' => $' . strtolower($table->name) . '->' . $column->name . ',';
            }

            $fields .= PHP_EOL . '            ';
        }

        return $fields;
    }
}
