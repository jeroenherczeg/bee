<?php

namespace Jeroenherczeg\Bee\Generators;

use Jeroenherczeg\Bee\ValueObjects\Replacements;

/**
 * Class SingleGenerator
 * @package Jeroenherczeg\Bee\Generators
 */
abstract class SingleGenerator extends AbstractGenerator
{
    /**
     * @return string
     */
    abstract protected function getStub();

    /**
     * @return array
     */
    abstract protected function getReplacements($table);

    /**
     * @return string
     */
    abstract protected function getFilenameFormat();

    /**
     * @return $this
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function generate()
    {
        $stubContent = $this->fs->get($this->getStubPath());

        foreach ($this->config->getTables() as $table) {
            $replacements = array_merge(
                (new Replacements($table))->getReplacements(),
                $this->getReplacements($table)
            );

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
        return $this->replace((new Replacements($table))->getReplacements(), $this->getFilenameFormat());
    }
}
