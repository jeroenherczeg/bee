<?php

namespace Jeroenherczeg\Bee\Generators\Laravel;

use Jeroenherczeg\Bee\Generators\SingleGenerator;
use Jeroenherczeg\Bee\ValueObjects\Replacements;

/**
 * Class RequestGenerator
 * @package Jeroenherczeg\Bee\Generators
 */
class RequestGenerator extends SingleGenerator
{
    /**
     * @return string
     */
    protected function getStub()
    {
        return 'laravel/request.stub';
    }

    /**
     * @return string
     */
    protected function getDestinationPath()
    {
        return 'app/Http/Requests/';
    }

    /**
     * Returns a string with the filename format
     *
     * @return string
     */
    protected function getFilenameFormat()
    {
        return '{{TableName}}Request.php';
    }

    /**
     * Returns an array with the necessary replacements
     *
     * @return array
     */
    protected function getReplacements($table)
    {
        $defaultReplacements = (new Replacements($table))->getReplacements();

        return array_merge($defaultReplacements, [
            'rules' => $this->buildRules($table),
        ]);
    }

    /**
     * @param $table
     *
     * @return string
     */
    private function buildRules($table)
    {
        $rules = '';

        foreach ($table->columns as $column) {
            if ($column->name !== 'timestamps' && $column->name !== 'id' && $column->name !== 'uuid' && $column->name !== 'remember_token') {
                    $rules .= '\'' . $column->name . '\' => \'required\',' . PHP_EOL . '                    ';
            }
        }

        return $rules;
    }
}
