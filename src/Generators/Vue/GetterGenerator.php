<?php

namespace Jeroenherczeg\Bee\Generators\Vue;

use Jeroenherczeg\Bee\Generators\CombinedGenerator;
use Jeroenherczeg\Bee\ValueObjects\Replacements;

/**
 * Class GetterGenerator
 * @package Jeroenherczeg\Bee\Generators\Vue
 */
class GetterGenerator extends CombinedGenerator
{
    /**
     * @var string
     */
    protected $partialStub = 'vue/partials/get.stub';

    /**
     * @return string
     */
    protected function getStub()
    {
        return 'vue/getters.stub';
    }

    /**
     * @return string
     */
    protected function getDestinationPath()
    {
        return 'resources/assets/js/state/';
    }

    /**
     * Returns an array with the necessary replacements
     *
     * @return array
     */
    protected function getReplacements($tables)
    {
        return [
            'getters' => $this->buildGetters($tables),
        ];
    }

    /**
     * Returns a string with the filename format
     *
     * @return string
     */
    protected function getFilenameFormat()
    {
        return 'getters.js';
    }

    /**
     * @return string
     */
    public function buildGetters($tables)
    {
        $partialContent = $this->fs->get($this->config->getStubsDir() . $this->partialStub);

        $get = '';

        foreach ($tables as $table) {
            $replacements = (new Replacements($table))->getReplacements();

            $get .= $this->replace($replacements, $partialContent);
            $get .= PHP_EOL;
        }

        return $get;
    }
}