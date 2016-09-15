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
                'table' => $str->plural($table->name),
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
                    $schema .= '$table->increments(\'' . $column->name . '\');' . PHP_EOL . '            ';
                    break;
                case 'uuid':
                    $schema .= '$table->uuid(\'' . $column->name . '\');' . PHP_EOL . '            ';
                    break;
                case 'description':
                    $schema .= '$table->text(\'' . $column->name . '\');' . PHP_EOL . '            ';
                    break;
                case 'timestamps':
                    $schema .= '$table->timestamps();' . PHP_EOL . '            ';
                    break;
                default:
                    $schema .= '$table->string(\'' . $column->name .'\');' . PHP_EOL . '            ';
            }

        }
        return $schema;
    }
}