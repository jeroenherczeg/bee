<?php

namespace Jeroenherczeg\Bee\Generators\Laravel;

use Jeroenherczeg\Bee\Generators\SingleGenerator;
use Jeroenherczeg\Bee\ValueObjects\Replacements;

/**
 * Class ModelGenerator
 * @package Jeroenherczeg\Bee\Generators
 */
class ModelGenerator extends SingleGenerator
{
    /**
     * @return string
     */
    protected function getStubPath()
    {
        return 'laravel/model.stub';
    }

    /**
     * @return string
     */
    protected function getDestinationPath()
    {
        return 'app/Models/Eloquent/';
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
            'fillable_fields' => $this->buildFillableFields($table),
        ]);
    }

    /**
     * Returns a string with the filename format
     *
     * @return string
     */
    protected function getFilenameFormat()
    {
        return '{{TableName}}.php';
    }

    /**
     * @param $table
     *
     * @return string
     */
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

        $fields = substr($fields, 0, -9);

        return $fields;
    }
}