<?php

namespace Jeroenherczeg\Bee\Generators\Vue;

use Jeroenherczeg\Bee\Generators\CombinedGenerator;
use Jeroenherczeg\Bee\ValueObjects\Replacements;

/**
 * Class StoreGenerator
 * @package Jeroenherczeg\Bee\Generators\Vue
 */
class StoreGenerator extends CombinedGenerator
{
    /**
     * @return string
     */
    protected function getStub()
    {
        return 'vue/store.stub';
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
            'store' => $this->buildStore($tables),
        ];
    }

    /**
     * Returns a string with the filename format
     *
     * @return string
     */
    protected function getFilenameFormat()
    {
        return 'store.js';
    }

    /**
     * @return string
     */
    public function buildStore($tables)
    {
        $store = '';

        foreach ($tables as $table) {
            $replacements = (new Replacements($table))->getReplacements();
            $store .= $this->replace($replacements, '{{table_names}}: [],' . PHP_EOL . '    ');
        }

        $store = substr($store, 0, -6);

        return $store;
    }
}