<?php

namespace Jeroenherczeg\Bee\Generators\Vue;

use Illuminate\Support\Str;
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
        $str = new Str();

        // Mutation
        $stub = $this->loadFile($this->config->path->stub->vue->mutations);
        
        $replacements = [
            'mutations' => $this->buildMutations(),
        ];

        $contents = $this->replace($replacements, $stub) . PHP_EOL;

        $fileName = 'mutations.js';
        $path = $this->config->path->output->vue->state;

        $this->saveFile($contents, $fileName, $path);

        $this->output->writeln('<info>Created vue mutation: ' . $fileName . '</info>');



        // Mutation types
        $stub = $this->loadFile($this->config->path->stub->vue->mutationtypes);

        $replacements = [
            'types' => $this->buildMutationTypes(),
        ];

        $contents = $this->replace($replacements, $stub) . PHP_EOL;

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

    public function buildMutationTypes()
    {
        $str = new Str();

        $stub = $this->loadFile($this->config->path->stub->vue->partials->mutationtype);

        $contents = '';

        foreach ($this->data->tables as $index => $table) {
            $replacements = [
                'plural_models_caps'=> strtoupper($str->plural($table->name)),
            ];

            $contents .= $this->replace($replacements, $stub) . PHP_EOL;

        }
        return $contents;
    }
}