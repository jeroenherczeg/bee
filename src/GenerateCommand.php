<?php

namespace Jeroenherczeg\Bee;

use Jeroenherczeg\Bee\Generators\MigrationGenerator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GenerateCommand
 * @package Jeroenherczeg\Bee
 */
class GenerateCommand extends AbstractCommand
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('generate')->setDescription('Scaffold from a .bee scaffolding file');
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
        
        $output->writeln('<comment>Let\'s get BEEzzzy!</comment>');

        $config = $this->loadConfig();
        
        $data = $this->loadScaffold();
        
        (new MigrationGenerator($data, $config, $output))->generate();

        $output->writeln('<comment>And we are done!.</comment>');
    }
}
