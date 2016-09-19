<?php

namespace Jeroenherczeg\Bee\Generators\Laravel;

use Jeroenherczeg\Bee\Generators\CombinedGenerator;
use Jeroenherczeg\Bee\ValueObjects\Replacements;

/**
 * Class RoutesGenerator
 * @package Jeroenherczeg\Bee\Generators
 */
class RoutesGenerator extends CombinedGenerator
{
    /**
     * @var string
     */
    protected $partialStub = 'laravel/partials/route.stub';

    /**
     * @return string
     */
    protected function getStub()
    {
        return 'laravel/routes.stub';
    }

    /**
     * @return string
     */
    protected function getDestinationPath()
    {
        return 'routes/';
    }

    /**
     * Returns an array with the necessary replacements
     *
     * @return array
     */
    protected function getReplacements($tables)
    {
        return [
            'routes' => $this->buildRoutes($tables),
        ];
    }

    /**
     * Returns a string with the filename format
     *
     * @return string
     */
    protected function getFilenameFormat()
    {
        return 'api.php';
    }

    /**
     * @param $tables
     *
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function buildRoutes($tables)
    {
        $partialContent = $this->fs->get($this->partialStub);

        $routes = '';

        foreach ($tables as $table) {
            $replacements = (new Replacements($table))->getReplacements();

            $routes .= $this->replace($replacements, $partialContent);
            $routes .= PHP_EOL;
        }

        return $routes;
    }
}