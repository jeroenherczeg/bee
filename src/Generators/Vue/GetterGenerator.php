<?php

namespace Jeroenherczeg\Bee\Generators\Vue;

use Illuminate\Support\Str;
use Jeroenherczeg\Bee\Generators\AbstractGenerator;

/**
 * Class GetterGenerator
 * @package Jeroenherczeg\Bee\Generators\Vue
 */
class GetterGenerator extends AbstractGenerator
{
    /**
     * Generate migrations
     */
    public function generate()
    {
        $stub = $this->loadFile($this->config->path->stub->vue->getters);

        $replacements = [
            'getters' => $this->buildGetters(),
        ];

        $contents = $this->replace($replacements, $stub);

        $fileName = 'getters.js';
        $path = $this->config->path->output->vue->state;

        $this->saveFile($contents, $fileName, $path);

        $this->output->writeln('<info>Created vue getter: ' . $fileName . '</info>');
    }

    public function buildGetters()
    {
        $str = new Str();
        $stub = $this->loadFile($this->config->path->stub->vue->partials->get);

        $get = '';
        foreach ($this->data->tables as $index => $table) {
            $replacements = [
                'plural_class'=> $str->plural($this->makeClassName($table->name)),
                'plural_model'=> $str->plural(strtolower($table->name)),
            ];

            $get .= $this->replace($replacements, $stub);
            $get .= PHP_EOL;
        }

        return $get;
    }
}