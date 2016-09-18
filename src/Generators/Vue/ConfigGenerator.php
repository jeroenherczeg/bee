<?php

namespace Jeroenherczeg\Bee\Generators\Vue;

use Jeroenherczeg\Bee\Generators\AbstractGenerator;

/**
 * Class ConfigGenerator
 * @package Jeroenherczeg\Bee\Generators\Vue
 */
class ConfigGenerator extends AbstractGenerator
{
    /**
     * Generate migrations
     */
    public function generate()
    {
        $stub = $this->loadFile($this->config->path->stub->vue->config);

        $replacements = [
            'site_root' => $this->data->env->dev->site,
            'api_root' => $this->data->env->dev->api,
        ];

        $contents = $this->replace($replacements, $stub);

        $fileName = 'config.js';
        $path = $this->config->path->output->vue->root;

        $this->saveFile($contents, $fileName, $path);

        $this->output->writeln('<info>Created vue config: ' . $fileName . '</info>');
    }
}