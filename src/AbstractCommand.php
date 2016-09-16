<?php

namespace Jeroenherczeg\Bee;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

abstract class AbstractCommand extends Command
{
    /**
     * Load the configuration file
     */
    protected function loadConfig()
    {
        $file = 'config.json';
        $path =  __DIR__ .'/../';

        return $this->loadJson($file, $path);
    }

    /**
     * Load the configuration file
     */
    protected function loadScaffold()
    {
        $file = '.bee';
        $path = getcwd() . '/';

        return $this->loadJson($file, $path);
    }

    /**
     * @param $file
     * @param $path
     *
     * @return mixed
     */
    protected function loadJson($file, $path)
    {
        if (!file_exists($path . $file)) {
            throw new RuntimeException($file . 'not found!');
        }

        $contents = file_get_contents($path . $file);

        if (!$this->isValidJson($contents)) {
            throw new RuntimeException($file . ' is not valid JSON!');
        }

        return json_decode($contents);
    }

    /**
     * @param $contents
     *
     * @return bool
     */
    protected function isValidJson($contents) {
        json_decode($contents);

        return (json_last_error() === JSON_ERROR_NONE);
    }

    /**
     * @param $source
     * @param $destination
     */
    protected function copyFile($source, $destination)
    {
        if (!copy($source, $destination)) {
            echo "failed to copy $source...\n";
        }
    }

    /**
     * @param $command
     */
    protected function install($command)
    {
        $process = new Process($command);
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        echo $process->getOutput();
    }

}