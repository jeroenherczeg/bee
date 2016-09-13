<?php

namespace Jeroenherczeg\Bee;

use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Beesie extends Command
{
    /**
     * Application Namespace
     */
    protected $namespace = 'App';

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('generate')->setDescription('Generate a bee');
    }

    /**
     * Execute the command.
     *
     * @param  InputInterface  $input
     * @param  OutputInterface  $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->readConfigFile();

        $output->writeln('<info>Namespace ' . $this->namespace . '...</info>');


        $output->writeln('<comment>Application ready! Build something amazing.</comment>');
    }


    /**
     * Read Config file
     */
    protected function readConfigFile()
    {
        $configFile = getcwd().'/.bee';

        if (!file_exists($configFile)) {
            throw new RuntimeException('No config file (.bee) found!');
        }

        $configContents = file_get_contents($configFile);

        if (!$this->isValidJson()) {
            throw new RuntimeException('The config file is not valid JSON!');
        }

        $config = json_decode($configContents);

        if(isset($config->namespace)) {
            $this->namespace = $config->namespace;
        }
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
