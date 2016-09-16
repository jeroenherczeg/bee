<?php

namespace Jeroenherczeg\Bee\Generators\Laravel;

use Illuminate\Support\Str;

/**
 * Class SeedGenerator
 * @package Jeroenherczeg\Bee\Generators
 */
class SeedGenerator extends AbstractGenerator
{
    /**
     * Generate migrations
     */
    public function generate()
    {
        $stub = $this->loadFile($this->config->path->stub->seed);

        foreach ($this->data->tables as $index => $table) {
            $replacements = [
                'namespace' => $this->config->default->namespace,
                'class' => ucfirst($table->name),
            ];

            $contents = $this->replace($replacements, $stub);

            $fileName = ucfirst($table->name) . 'TableSeeder.php';
            $path = $this->config->path->output->seeds;

            $this->saveFile($contents, $fileName, $path);

            $this->output->writeln('<info>Created seed: ' . $fileName . '</info>');
        }
    }
}