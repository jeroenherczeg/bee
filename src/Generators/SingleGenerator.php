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
     * @return string
     */
    protected function getFilename($table)
    {
        return $this->replace($this->getDefaultReplacements($table), $this->getFilenameFormat());
    }
}
