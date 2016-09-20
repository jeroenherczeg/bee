<?php

namespace Jeroenherczeg\Bee\Generators\Vue;

use Jeroenherczeg\Bee\Generators\CombinedGenerator;

/**
 * Class ConfigGenerator
 * @package Jeroenherczeg\Bee\Generators\Vue
 */
class ConfigGenerator extends CombinedGenerator
{
    /**
     * @return string
     */
    protected function getStub()
    {
        return 'vue/config.stub';
    }

    /**
     * @return string
     */
    protected function getDestinationPath()
    {
        return 'resources/assets/js/';
    }

    /**
     * Returns an array with the necessary replacements
     *
     * @return array
     */
    protected function getReplacements($tables)
    {
        return [
            'site_root' => $this->config->getSiteRoot(),
            'api_root' => $this->config->getApiRoot(),
        ];
    }

    /**
     * Returns a string with the filename format
     *
     * @return string
     */
    protected function getFilenameFormat()
    {
        return 'config.js';
    }
}
