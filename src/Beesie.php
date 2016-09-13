<?php

namespace Jeroenherczeg\Bee;

use Symfony\Component\Process\Process;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Beesie extends Command
{
    /**
     * Application Name
     */
    protected $name;

    /**
     * Application Namespace
     */
    protected $namespace;

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('generate')
            ->setDescription('Generate a bee')
            ->addArgument('name', InputArgument::REQUIRED, 'How do you want to call your project?')
            ->addArgument('namespace', InputArgument::REQUIRED, 'How do you want to call your namespace?');
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
        $this->name = $input->getArgument('name');
        $this->namespace = $input->getArgument('namespace');
        $output->writeln('<info>Crafting ' . $this->name . '...</info>');

        $output->writeln('<info>Namespace ' . $this->namespace . '...</info>');

        $process = new Process('ls -lsa');
        $process->run();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        echo $process->getOutput();

        $output->writeln('<comment>Application ready! Build something amazing.</comment>');
    }
}
