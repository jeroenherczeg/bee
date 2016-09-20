<?php

namespace Jeroenherczeg\Bee\Generators\Vue;

use Jeroenherczeg\Bee\Generators\CombinedGenerator;
use Jeroenherczeg\Bee\ValueObjects\Replacements;


/**
 * Class MutationGenerator
 * @package Jeroenherczeg\Bee\Generators\Vue
 */
class MutationGenerator extends CombinedGenerator
{
    /**
     * @var string
     */
    protected $partialStub = 'vue/partials/mutation.stub';

    /**
     * @return string
     */
    protected function getStub()
    {
        return 'vue/mutations.stub';
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
            'mutations' => $this->buildMutations($tables),
        ];
    }

    /**
     * Returns a string with the filename format
     *
     * @return string
     */
    protected function getFilenameFormat()
    {
        return 'mutations.js';
    }

    /**
     * @param $tables
     *
     * @return string
     */
    public function buildMutations($tables)
    {
        $partialContent = $this->fs->get($this->config->getStubsDir() . $this->partialStub);

        $mutations = '';

        foreach ($tables as $table) {
            $replacements = (new Replacements($table))->getReplacements();

            $mutations .= $this->replace($replacements, $partialContent);
            $mutations .= PHP_EOL;
        }

        $mutations = substr($mutations, 0, -2);

        return $mutations;
    }
}