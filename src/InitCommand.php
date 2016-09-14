<?php

namespace Jeroenherczeg\Bee;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class InitCommand
 * @package Jeroenherczeg\Bee
 */
class InitCommand extends AbstractCommand
{
    /**
     * InputInterface
     */
    protected $input;

    /**
     * OutputInterface
     */
    protected $output;

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('init')->setDescription('Respond to questions to generate a .bee file');
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
        $this->input = $input;
        $this->output = $output;

        $output->writeln('<comment>Not implemented yet :(</comment>');
    }
}
