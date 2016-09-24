<?php

namespace Jeroenherczeg\Bee\Generators;

/**
 * Class MultiGenerator
 * @package Jeroenherczeg\Bee\Generators
 */
abstract class MultiGenerator extends AbstractGenerator
{
    /**
     * @return array
     */
    abstract protected function getStubs();

    /**
     * @return $this
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function generate()
    {
        foreach ($this->getStubs() as $stub => $filename) {

            $stubContent = $this->fs->get($this->config->getStubsDir() . $stub);

            $replacements = [
                'namespace' => $this->config->getNamespace(),
                'timestamp' => date('Y_m_d_' . str_replace('.', '', microtime(true))),
            ];

            $content = $this->replace($replacements, $stubContent);

            $filename = $this->replace($replacements, $filename);

            $this->storeFile($filename, $content);
        }

        return $this;
    }

    public function getDestinationPath()
    {
        return $this->config->getProjectDir() . '/';
    }
}
