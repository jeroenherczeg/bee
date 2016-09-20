<?php

namespace Jeroenherczeg\Bee\Generators\Laravel;

use Jeroenherczeg\Bee\Generators\SingleGenerator;
use Jeroenherczeg\Bee\ValueObjects\Replacements;

/**
 * Class FactoryGenerator
 * @package Jeroenherczeg\Bee\Generators
 */
class FactoryGenerator extends SingleGenerator
{
    /**
     * @return string
     */
    protected function getStub()
    {
        return 'laravel/factory.stub';
    }

    /**
     * @return string
     */
    protected function getDestinationPath()
    {
        return 'database/factories/';
    }

    /**
     * Returns a string with the filename format
     *
     * @return string
     */
    protected function getFilenameFormat()
    {
        return '{{TableName}}Factory.php';
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
            'fields' => $this->buildFields($table),
        ]);
    }
    
    private function buildFields($table)
    {
        $fields = '';
        foreach ($table->columns as $column) {
            switch ($column->name) {
                case 'id':
                case 'timestamps':
                    break;
                case 'uuid':
                    $fields .= '\'' . $column->name . '\' => $faker->uuid,' . PHP_EOL . '        ';
                    break;
                case 'description':
                    $fields .= '\'' . $column->name . '\' => $faker->realText(100),' . PHP_EOL . '        ';
                    break;
                case 'url':
                    $fields .= '\'' . $column->name . '\' => $faker->url,' . PHP_EOL . '        ';
                    break;
                case 'email':
                    $fields .= '\'' . $column->name . '\' => uniqid() . $faker->email,' . PHP_EOL . '        ';
                    break;
                case 'remember_token':
                case 'token':
                    $fields .= '\'' . $column->name . '\' => $faker->sha1,' . PHP_EOL . '        ';
                    break;
                case 'username':
                    $fields .= '\'' . $column->name . '\' => $faker->userName . uniqid(),' . PHP_EOL . '        ';
                    break;
                case 'firstname':
                    $fields .= '\'' . $column->name . '\' => $faker->firstName . uniqid(),' . PHP_EOL . '        ';
                    break;
                case 'lastname':
                    $fields .= '\'' . $column->name . '\' => $faker->lastName . uniqid(),' . PHP_EOL . '        ';
                    break;
                default:
                    $fields .= '\'' . $column->name . '\' => $faker->sentence,' . PHP_EOL . '        ';
            }
        }
        return $fields;
    }
}