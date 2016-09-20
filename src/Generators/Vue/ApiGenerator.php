<?php

namespace Jeroenherczeg\Bee\Generators\Vue;

use Jeroenherczeg\Bee\Generators\CombinedGenerator;
use Jeroenherczeg\Bee\ValueObjects\Replacements;

/**
 * Class ApiGenerator
 * @package Jeroenherczeg\Bee\Generators\Vue
 */
class ApiGenerator extends CombinedGenerator
{
    /**
     * @var string
     */
    protected $partialStub = 'vue/partials/api_routes.stub';

    /**
     * @return string
     */
    protected function getStub()
    {
        return 'vue/api.stub';
    }

    /**
     * @return string
     */
    protected function getDestinationPath()
    {
        return 'resources/assets/js/utilities/';
    }

    /**
     * Returns an array with the necessary replacements
     *
     * @return array
     */
    protected function getReplacements($tables)
    {
        return [
            'api' => $this->buildApi($tables),
        ];
    }

    /**
     * Returns a string with the filename format
     *
     * @return string
     */
    protected function getFilenameFormat()
    {
        return 'api.js';
    }

    /**
     * @return string
     */
    private function buildApi($tables)
    {
        $partialContent = $this->fs->get($this->config->getStubsDir() . $this->partialStub);

        $routes = '';

        foreach ($tables as $table) {
            $replacements = (new Replacements($table))->getReplacements();

            $routes .= $this->replace($replacements, $partialContent);
            $routes .= PHP_EOL;
        }

        return $routes;
    }
}