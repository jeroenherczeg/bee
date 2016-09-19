<?php

namespace Jeroenherczeg\Bee\Generators;


use Illuminate\Support\Collection;


/**
 * Class AbstractGenerator
 * @package Jeroenherczeg\Bee\Generators
 */
abstract class SingleGenerator extends AbstractGenerator
{
    /**
     * @return $this
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function generate()
    {
        $stubContent = $this->fs->get($this->getStubPath());

        foreach ($this->config->getTables() as $table) {

            $replacements = $this->getDefaultReplacements($table)->merge($this->getReplacements($table));

            $content = $this->replace($replacements, $stubContent);
            
            $filename = $this->getFilename($table);
            
            $this->storeFile($filename, $content);

        }

        return $this;
    }


    /**
     * @return Collection
     */
    private function getDefaultReplacements($table)
    {
        return new Collection([
            'namespace'   => $this->config->getNamespace(),
            'TableName'   => $this->str->studly($table->name),
            'TableNames'  => $this->str->studly($this->str->plural($table->name)),
            'table_name'  => $this->str->lower($table->name),
            'table_names' => $this->str->lower($this->str->plural($table->name)),
            'TABLE_NAME'  => $this->str->upper($table->name),
            'TABLE_NAMES' => $this->str->upper($this->str->plural($table->name)),
            'timestamp'   => date('Y_m_d_His'),
        ]);
    }

    /**
     * @return string
     */
    protected function getFilename($table)
    {
        return $this->replace($this->getDefaultReplacements($table), $this->getFilenameFormat());
    }
}
