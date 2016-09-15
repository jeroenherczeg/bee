<?php

namespace Jeroenherczeg\Bee\Generators;

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
        $stub = $this->loadFile($this->config->path->stub->migration);

        foreach ($this->data->tables as $index => $table) {
            $replacements = [
                'class' => $table->name,
                'table' => $table->name,
                'schema_up' => $this->buildSchemaUp($table)
            ];

            $contents = $this->replace($replacements, $stub);

            $fileName = date("Y_m_d") . '_' . str_pad($index, 6, "0", STR_PAD_LEFT) . '_create_' . strtolower($table->name) . '_table.php';
            $path = $this->config->path->output->migrations;

            $this->saveFile($contents, $fileName, $path);

            $this->output->writeln('<info>Creating migration: ' . $fileName . '</info>');
        }
    }
    
    private function buildSchemaUp($table)
    {
        $schema = '';
        foreach ($table->columns as $column) {
            $schema .= '$table->string(\'' . $column->name .'\');' . PHP_EOL . '            ';
        }
        return $schema;
    }
}