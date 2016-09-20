<?php

namespace Jeroenherczeg\Bee\Generators\Laravel;

use Jeroenherczeg\Bee\Generators\SingleGenerator;
use Jeroenherczeg\Bee\ValueObjects\Replacements;

/**
 * Class TransformerGenerator
 * @package Jeroenherczeg\Bee\Generators
 */
class TransformerGenerator extends SingleGenerator
{
    /**
     * @return string
     */
    protected function getStub()
    {
        return 'laravel/transformer.stub';
    }

    /**
     * @return string
     */
    protected function getDestinationPath()
    {
        return 'app/Models/Transformers/';
    }

    /**
     * Returns a string with the filename format
     *
     * @return string
     */
    protected function getFilenameFormat()
    {
        return '{{TableName}}Transformer.php';
    }

    /**
     * Returns an array with the necessary replacements
     *
     * @return array
     */
    protected function getReplacements($table)
    {
        $defaultReplacements = (new Replacements($table))->getReplacements();

        return array_merge($defaultReplacements, [
            'fields' => $this->buildFields($table),
        ]);
    }

    /**
     * @param $table
     *
     * @return string
     */
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
