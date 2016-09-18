<?php

namespace Jeroenherczeg\Bee\Generators\Vue;

use Illuminate\Support\Str;
use Jeroenherczeg\Bee\Generators\AbstractGenerator;

/**
 * Class ActionGenerator
 * @package Jeroenherczeg\Bee\Generators\Vue
 */
class ActionGenerator extends AbstractGenerator
{
    /**
     * Generate migrations
     */
    public function generate()
    {
        $stub = $this->loadFile($this->config->path->stub->vue->actions);

        $replacements = [
            'actions' => $this->buildActions(),
        ];

        $contents = $this->replace($replacements, $stub);

        $fileName = 'actions.js';
        $path = $this->config->path->output->vue->state;

        $this->saveFile($contents, $fileName, $path);

        $this->output->writeln('<info>Created vue actions: ' . $fileName . '</info>');
    }

    public function buildActions()
    {
        $str = new Str();
        $stub = $this->loadFile($this->config->path->stub->vue->partials->action);

        $actions = '';
        foreach ($this->data->tables as $index => $table) {
            $replacements = [
                'plural_class' => $str->plural($this->makeClassName($table->name)),
                'plural_model' => $str->plural($table->name),
                'plural_model_caps'=> strtoupper($str->plural($table->name)),
            ];

            $actions .= $this->replace($replacements, $stub);
            $actions .= PHP_EOL;
        }

        return $actions;
    }
}