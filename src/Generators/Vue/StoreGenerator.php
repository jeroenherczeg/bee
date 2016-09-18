<?php

namespace Jeroenherczeg\Bee\Generators\Vue;

use Illuminate\Support\Str;
use Jeroenherczeg\Bee\Generators\AbstractGenerator;

/**
 * Class StoreGenerator
 * @package Jeroenherczeg\Bee\Generators\Vue
 */
class StoreGenerator extends AbstractGenerator
{
    /**
     * Generate migrations
     */
    public function generate()
    {
        $stub = $this->loadFile($this->config->path->stub->vue->store);

        $replacements = [
            'store' => $this->buildStore(),
        ];

        $contents = $this->replace($replacements, $stub);

        $fileName = 'store.js';
        $path = $this->config->path->output->vue->state;

        $this->saveFile($contents, $fileName, $path);

        $this->output->writeln('<info>Created vue store: ' . $fileName . '</info>');
    }

    public function buildStore()
    {
        $str = new Str();
        $store = '';

        foreach ($this->data->tables as $index => $table) {
            $store .= $str->plural(strtolower($table->name)) . ': [],' . PHP_EOL;
        }

        $store = substr($store, 0, -2);

        return $store;
    }
}