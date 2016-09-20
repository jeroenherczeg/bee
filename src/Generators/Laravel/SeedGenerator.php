<?php

namespace Jeroenherczeg\Bee\Generators\Laravel;

use Jeroenherczeg\Bee\Generators\SingleGenerator;
use Jeroenherczeg\Bee\ValueObjects\Replacements;

/**
 * Class SeedGenerator
 * @package Jeroenherczeg\Bee\Generators
 */
class SeedGenerator extends SingleGenerator
{
    /**
     * @return string
     */
    protected function getStub()
    {
        return 'laravel/seed.stub';
    }

    /**
     * @return string
     */
    protected function getDestinationPath()
    {
        return 'database/seeds/';
    }

    /**
     * Returns a string with the filename format
     *
     * @return string
     */
    protected function getFilenameFormat()
    {
        return '{{TableNames}}TableSeeder.php';
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