<?php

namespace Jeroenherczeg\Bee;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;

abstract class AbstractCommand extends Command
{
    /**
     * @var
     */
    private $configFile;

    /**
     * @var null
     */
    protected $config = null;

    /**
     * Load the configuration file
     */
    protected function loadConfig()
    {
        $this->configFile = __DIR__ .'/../config.json';

        if (!file_exists($this->configFile)) {
            throw new RuntimeException('No config file found!');
        }

        $contents = file_get_contents($this->configFile);

        if (!$this->isValidJson($contents)) {
            throw new RuntimeException('The config file is not valid JSON!');
        }

        $this->config = json_decode($contents);
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
}