<?php

namespace Jeroenherczeg\Bee\Generators;

use Illuminate\Support\Str;

/**
 * Class FactoryGenerator
 * @package Jeroenherczeg\Bee\Generators
 */
class FactoryGenerator extends AbstractGenerator
{
    /**
     * Generate migrations
     */
    public function generate()
    {
        $str = new Str();
        $stub = $this->loadFile($this->config->path->stub->factory);

        foreach ($this->data->tables as $index => $table) {
            $replacements = [
                'namespace' => $this->config->default->namespace,
                'class' => ucfirst($table->name),
                'fields' => $this->buildFields($table)
            ];

            $contents = $this->replace($replacements, $stub);

            $fileName = ucfirst($table->name) . 'Factory.php';
            $path = $this->config->path->output->factories;

            $this->saveFile($contents, $fileName, $path);

            $this->output->writeln('<info>Created factory: ' . $fileName . '</info>');
        }
    }

    private function buildFields($table)
    {
        $fields = '';
        foreach ($table->columns as $column) {
            switch ($column->name) {
                case 'id':
                case 'timestamps':
                    break;
                case 'description':
                    $fields .= '\'' . $column->name . '\' => $faker->realText(100),' . PHP_EOL . '          ';;
                    break;
                case 'email':
                    $fields .= '\'' . $column->name . '\' => $faker->email,' . PHP_EOL . '        ';;
                    break;
                default:
                    $fields .= '\'' . $column->name . '\' => $faker->sentence,' . PHP_EOL . '        ';;
            }
        }
        return $fields;
    }
}