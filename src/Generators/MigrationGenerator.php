<?php

namespace Jeroenherczeg\Bee\Generators;
use Illuminate\Support\Str;

/**
 * Class MigrationGenerator
 * @package Jeroenherczeg\Bee\Generators
 */
class MigrationGenerator extends AbstractGenerator
{
    /**
     * Generate a migration
     */
    public function generate()
    {
        $str = new Str();
        $stub = $this->loadFile($this->config->path->stub->migration);

        foreach ($this->data->tables as $index => $table) {
            $replacements = [
                'class' => 'Create' . ucfirst($str->plural($table->name)) . 'Table',
                'table' =>  strtolower($str->plural($table->name)),
                'schema_up' => $this->buildSchemaUp($table)
            ];

            $contents = $this->replace($replacements, $stub);

            $fileName = date("Y_m_d") . '_' . str_pad($index, 6, "0", STR_PAD_LEFT) . '_create_' . strtolower($str->plural($table->name)) . '_table.php';
            $path = $this->config->path->output->migrations;

            $this->saveFile($contents, $fileName, $path);

            $this->output->writeln('<info>Creating migration: ' . $fileName . '</info>');
        }
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