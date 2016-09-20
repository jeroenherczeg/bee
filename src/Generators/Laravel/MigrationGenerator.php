<?php

namespace Jeroenherczeg\Bee\Generators\Laravel;

use Jeroenherczeg\Bee\Generators\SingleGenerator;
use Jeroenherczeg\Bee\ValueObjects\Replacements;

/**
 * Class MigrationGenerator
 * @package Jeroenherczeg\Bee\Generators
 */
class MigrationGenerator extends SingleGenerator
{
    /**
     * @return string
     */
    protected function getStub()
    {
        return 'laravel/migration.stub';
    }

    /**
     * @return string
     */
    protected function getDestinationPath()
    {
        return 'database/migrations/';
    }

    /**
     * Returns a string with the filename format
     *
     * @return string
     */
    protected function getFilenameFormat()
    {
        return '{{timestamp}}_create_{{table_names}}_table.php';
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
            'schema_up' => $this->buildSchemaUp($table),
        ]);
    }

    private function buildSchemaUp($table)
    {
        $schema = '';
        foreach ($table->columns as $column) {
            switch ($column->name) {
                case 'id':
                    $schema .= '$table->increments(\'' . $column->name . '\')';
                    break;
                case 'uuid':
                    $schema .= '$table->uuid(\'' . $column->name . '\')';
                    break;
                case 'description':
                    $schema .= '$table->text(\'' . $column->name . '\')';
                    break;
                case 'remember_token':
                    $schema .= '$table->rememberToken()';
                    break;
                case 'timestamps':
                    $schema .= '$table->timestamps()';
                    break;
                default:
                    $schema .= '$table->string(\'' . $column->name .'\')';
            }

            if (isset($column->modifiers)) {
                if (isset($column->modifiers->nullable)) {
                    $schema .= '->nullable()';
                }
                if (isset($column->modifiers->unique)) {
                    $schema .= '->unique()';
                }
                if (isset($column->modifiers->index)) {
                    $schema .= '->index()';
                }
                if (isset($column->modifiers->default)) {
                    $schema .= '->default(';
                    if (!is_int($column->modifiers->default)) {
                        $schema .= '\'';
                    }
                    $schema .= $column->modifiers->default;
                    if (!is_int($column->modifiers->default)) {
                        $schema .= '\'';
                    }
                    $schema .= ')';
                }
            }

            $schema .= ';' . PHP_EOL . '            ';

        }
        return $schema;
    }
}
