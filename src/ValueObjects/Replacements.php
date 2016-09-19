<?php

namespace Jeroenherczeg\Bee\ValueObjects;

use Illuminate\Support\Str;

/**
 * Class Replacements
 * @package Jeroenherczeg\Bee\ValueObjects
 */
class Replacements
{
    /**
     * @var
     */
    protected $replacements = [];

    /**
     * @var
     */
    protected $table;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Str
     */
    protected $str;

    /**
     * Replacements constructor.
     *
     * @param $table
     */
    public function __construct($table)
    {
        $this->table = $table;
        $this->config = new Config();
        $this->str = new Str();
    }

    /**
     * @return array
     */
    public function getReplacements()
    {
        return [
            'namespace'   => $this->config->getNamespace(),
            'TableName'   => $this->str->studly($this->table->name),
            'TableNames'  => $this->str->studly($this->str->plural($this->table->name)),
            'table_name'  => $this->str->lower($this->table->name),
            'table_names' => $this->str->lower($this->str->plural($this->table->name)),
            'TABLE_NAME'  => $this->str->upper($this->table->name),
            'TABLE_NAMES' => $this->str->upper($this->str->plural($this->table->name)),
            'timestamp'   => date('Y_m_d_His'),
        ];
    }
}
