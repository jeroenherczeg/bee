<?php

namespace Jeroenherczeg\Bee\Generators;

/**
 * Class CombinedGenerator
 * @package Jeroenherczeg\Bee\Generators
 */
abstract class CombinedGenerator extends AbstractGenerator
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

        $replacements = $this->getReplacements($this->config->getTables());

        $content = $this->replace($replacements, $stubContent);

        $filename = $this->getFilename();

        $this->storeFile($filename, $content);

        return $this;
    }

    /**
     * @return string
     */
    protected function getFilename()
    {
        return $this->getFilenameFormat();
    }
}
