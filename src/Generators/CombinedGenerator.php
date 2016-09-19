<?php

namespace Jeroenherczeg\Bee\Generators;

use Illuminate\Support\Collection;

/**
 * Class CombinedGenerator
 * @package Jeroenherczeg\Bee\Generators
 */
abstract class CombinedGenerator extends AbstractGenerator
{
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
