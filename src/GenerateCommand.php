<?php

namespace Jeroenherczeg\Bee;

use Jeroenherczeg\Bee\Generators\ControllerGenerator;
use Jeroenherczeg\Bee\Generators\MigrationGenerator;
use Jeroenherczeg\Bee\Generators\ModelGenerator;
use Jeroenherczeg\Bee\Generators\RequestGenerator;
use Jeroenherczeg\Bee\Generators\TransformerGenerator;
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

        /**
         * To do
         *
         * install fractal
         * copy base controller
         */

        (new MigrationGenerator($data, $config, $output))->generate();
        (new ModelGenerator($data, $config, $output))->generate();
        (new ControllerGenerator($data, $config, $output))->generate();
        (new TransformerGenerator($data, $config, $output))->generate();
        (new RequestGenerator($data, $config, $output))->generate();

        $output->writeln('<comment>And we are done!.</comment>');
    }
}
