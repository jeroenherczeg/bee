<?php

namespace Jeroenherczeg\Bee\Generators\Vue;

use Illuminate\Support\Str;
use Jeroenherczeg\Bee\Generators\AbstractGenerator;

/**
 * Class ApiGenerator
 * @package Jeroenherczeg\Bee\Generators\Vue
 */
class ApiGenerator extends AbstractGenerator
{
    /**
     * Generate migrations
     */
    public function generate()
    {
        $stub = $this->loadFile($this->config->path->stub->vue->api);

        $replacements = [
            'api' => $this->buildApi(),
        ];

        $contents = $this->replace($replacements, $stub);

        $fileName = 'api.js';
        $path = $this->config->path->output->vue->utilities;

        $this->saveFile($contents, $fileName, $path);

        $this->output->writeln('<info>Created vue api: ' . $fileName . '</info>');
    }

    public function buildApi()
    {
        $str = new Str();
        $stub = $this->loadFile($this->config->path->stub->vue->partials->api_routes);

        $routes = '';
        foreach ($this->data->tables as $index => $table) {
            $replacements = [
                'plural_class' => $str->plural($this->makeClassName($table->name)),
                'plural_model' => $str->plural($table->name),
            ];

            $routes .= $this->replace($replacements, $stub);
            $routes .= PHP_EOL;
        }

        return $routes;
    }
}