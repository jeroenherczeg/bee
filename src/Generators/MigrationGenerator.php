<?php

namespace Jeroenherczeg\Bee\Generators;

/**
 * Class MigrationGenerator
 * @package Jeroenherczeg\Bee\Generators
 */
class MigrationGenerator extends AbstractGenerator
{
    /**
     * @var
     */
    protected $data;

    /**
     * @var
     */
    protected $config;

    /**
     * @var
     */
    protected $output;

    /**
     * MigrationGenerator constructor.
     *
     * @param $data
     * @param $config
     */
    public function __construct($data, $config, $output)
    {
        $this->data = $data;
        $this->config = $config;
        $this->output = $output;
    }

    /**
     * Generate a migration
     */
    public function generate()
    {
        $stub = $this->loadFile($this->config->path->stub->migration);

        foreach ($this->data->tables as $table) {
            $replacements = [
                'class' => $table->name,
                'table' => $table->name,
                'schema_up' => $this->buildSchemaUp($table)
            ];

            $contents = $this->replace($replacements, $stub);

            $fileName = '2014_10_12_000000_create_' . strtolower($table->name) . '_table.php';
            $path = $this->config->path->output->migrations;

            $this->saveFile($contents, $fileName, $path);

            $this->output->writeln('<info>Creating migration: ' . $fileName . '</info>');
        }
    }
    
    public function buildSchemaUp($table)
    {
        $schema = '';
        foreach ($table->columns as $column) {
            $schema .= '$table->string(\'' . $column->name .'\');' . PHP_EOL . '            ';
        }
        return $schema;
    }
}