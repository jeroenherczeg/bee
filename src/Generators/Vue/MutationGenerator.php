<?php

namespace Jeroenherczeg\Bee\Generators\Vue;

use Jeroenherczeg\Bee\Generators\AbstractGenerator;

/**
 * Class MutationGenerator
 * @package Jeroenherczeg\Bee\Generators\Vue
 */
class MutationGenerator extends AbstractGenerator
{
    /**
     * Generate migrations
     */
    public function generate()
    {
        // Mutation
        $stub = $this->loadFile($this->config->path->stub->vue->mutations);
        
        $replacements = [
            'mutations' => $this->buildMutations(),
        ];

        $contents .= $this->replace($replacements, $stub) . PHP_EOL;

        $fileName = 'mutations.js';
        $path = $this->config->path->output->vue->state;

        $this->saveFile($contents, $fileName, $path);

        $this->output->writeln('<info>Created vue mutation types: ' . $fileName . '</info>');



        // Mutation types
        $stub = $this->loadFile($this->config->path->stub->vue->partials->mutation-type);

        $contents = '';

        foreach ($this->data->tables as $index => $table) {
            $replacements = [
                'model_caps' => strtoupper($table->name),
            ];

            $contents .= $this->replace($replacements, $stub) . PHP_EOL;

        }

        $fileName = 'mutation-types.js';
        $path = $this->config->path->output->vue->state;

        $this->saveFile($contents, $fileName, $path);

        $this->output->writeln('<info>Created vue mutation types: ' . $fileName . '</info>');
    }

    public function buildMutations()
    {
        $str = new Str();
        $stub = $this->loadFile($this->config->path->stub->vue->partials->mutation);

        $mutations = '';
        foreach ($this->data->tables as $index => $table) {
            $replacements = [
                'plural_models'=> $str->plural($table->name),
                'plural_models_caps'=> strtoupper($str->plural($table->name)),
            ];

            $mutations .= $this->replace($replacements, $stub);
            $mutations .= PHP_EOL;
        }

        $mutations = substr($mutations, 0, -2);

        return $mutations;
    }
}