<?php

namespace Jeroenherczeg\Bee\Generators\Laravel;

use Illuminate\Support\Str;
use Jeroenherczeg\Bee\Generators\AbstractGenerator;

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
                'class' => $this->makeClassName($table->name),
            ];

            $contents = $this->replace($replacements, $stub);

            $fileName = $this->makeClassName($table->name) . 'TableSeeder.php';
            $path = $this->config->path->output->seeds;

            $this->saveFile($contents, $fileName, $path);

            $this->output->writeln('<info>Created seed: ' . $fileName . '</info>');
        }
    }
}