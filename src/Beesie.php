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

    protected function readConfigFile()
    {
        $configFile = getcwd().'/.bee';

        if (!file_exists($configFile)) {
            throw new RuntimeException('No config file (.bee) found!');
        }

        $configContents = file_get_contents($configFile);

        $config = json_decode($configContents);

        $this->namespace = $config->namespace;
    }
}
