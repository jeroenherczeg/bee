<?php

namespace Jeroenherczeg\Bee\Generators\Vue;

use Jeroenherczeg\Bee\Generators\CombinedGenerator;
use Jeroenherczeg\Bee\ValueObjects\Replacements;

/**
 * Class ActionGenerator
 * @package Jeroenherczeg\Bee\Generators\Vue
 */
class ActionGenerator extends CombinedGenerator
{
    /**
     * @var string
     */
    protected $partialStub = 'vue/partials/action.stub';

    /**
     * @return string
     */
    protected function getStub()
    {
        return 'vue/actions.stub';
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
            'actions' => $this->buildActions($tables),
        ];
    }

    /**
     * Returns a string with the filename format
     *
     * @return string
     */
    protected function getFilenameFormat()
    {
        return 'actions.js';
    }

    /**
     * @return string
     */
    public function buildActions($tables)
    {
        $partialContent = $this->fs->get($this->config->getStubsDir() . $this->partialStub);

        $actions = '';

        foreach ($tables as $table) {
            $replacements = (new Replacements($table))->getReplacements();

            $actions .= $this->replace($replacements, $partialContent);
            $actions .= PHP_EOL;
        }

        return $actions;
    }
}