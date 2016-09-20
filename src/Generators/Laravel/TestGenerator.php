<?php

namespace Jeroenherczeg\Bee\Generators\Laravel;

use Jeroenherczeg\Bee\Generators\SingleGenerator;
use Jeroenherczeg\Bee\ValueObjects\Replacements;

/**
 * Class TestGenerator
 * @package Jeroenherczeg\Bee\Generators
 */
class TestGenerator extends SingleGenerator
{
    /**
     * @return string
     */
    protected function getStub()
    {
        return 'laravel/test.stub';
    }

    /**
     * @return string
     */
    protected function getDestinationPath()
    {
        return 'tests/';
    }

    /**
     * Returns a string with the filename format
     *
     * @return string
     */
    protected function getFilenameFormat()
    {
        return '{{TableName}}ApiTest.php';
    }

    /**
     * Returns an array with the necessary replacements
     *
     * @return array
     */
    protected function getReplacements($table)
    {
        $defaultReplacements = (new Replacements($table))->getReplacements();

        return array_merge($defaultReplacements, []);
    }
}