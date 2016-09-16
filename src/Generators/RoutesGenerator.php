<?php

namespace Jeroenherczeg\Bee\Generators;

use Illuminate\Support\Str;

/**
 * Class RoutesGenerator
 * @package Jeroenherczeg\Bee\Generators
 */
class RoutesGenerator extends AbstractGenerator
{
    /**
     * Generate migrations
     */
    public function generate()
    {
        $stub = $this->loadFile($this->config->path->stub->routes);
        
        $replacements = [
            'routes' => $this->buildRoutes(),
        ];

        $contents = $this->replace($replacements, $stub);

        $fileName = 'api.php';
        $path = $this->config->path->output->routes;

        $this->saveFile($contents, $fileName, $path);

        $this->output->writeln('<info>Created routes: ' . $fileName . '</info>');

        foreach ($this->data->tables as $index => $table) {

        }
    }

    public function buildRoutes()
    {
        $str = new Str();
        $stub = $this->loadFile($this->config->path->stub->route);

        $routes = '';
        foreach ($this->data->tables as $index => $table) {
            $replacements = [
                'class' => ucfirst($table->name),
                'models' => strtolower($str->plural($table->name)),
            ];

            $routes .= $this->replace($replacements, $stub);
            $routes .= PHP_EOL;
        }

        return $routes;
    }
}