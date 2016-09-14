<?php

namespace Jeroenherczeg\Bee;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;

abstract class AbstractCommand extends Command
{
    private $configFile;
    
    protected $config;
    
    public function __construct($name)
    {
        parent::__construct($name);
        
        $this->loadConfig();
    }
    
    private function loadConfig()
    {
        $this->config = __DIR__;
        //if (!file_exists($this->configFile)) {
        //    throw new RuntimeException('No config file (.bee) found!');
        //}
        //$this->config =
    }
}