<?php

namespace Jeroenherczeg\Bee;

use Jeroenherczeg\Bee\Generators\ControllerGenerator;
use Jeroenherczeg\Bee\Generators\FactoryGenerator;
use Jeroenherczeg\Bee\Generators\MigrationGenerator;
use Jeroenherczeg\Bee\Generators\ModelGenerator;
use Jeroenherczeg\Bee\Generators\RequestGenerator;
use Jeroenherczeg\Bee\Generators\RoutesGenerator;
use Jeroenherczeg\Bee\Generators\SeedGenerator;
use Jeroenherczeg\Bee\Generators\TestGenerator;
use Jeroenherczeg\Bee\Generators\TransformerGenerator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

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

        $this->install('composer require league/fractal');

        $this->configurePHPUnit();

        (new MigrationGenerator($data, $config, $output))->generate();
        (new ModelGenerator($data, $config, $output))->generate();
        (new ControllerGenerator($data, $config, $output))->generate();
        (new TransformerGenerator($data, $config, $output))->generate();
        (new RequestGenerator($data, $config, $output))->generate();
        (new FactoryGenerator($data, $config, $output))->generate();
        (new SeedGenerator($data, $config, $output))->generate();
        (new TestGenerator($data, $config, $output))->generate();
        (new RoutesGenerator($data, $config, $output))->generate();

        $output->writeln('<comment>And we are done!.</comment>');
    }
    
    public function configurePHPUnit()
    {
        $this->copyFile(__DIR__ . '/../assets/files/phpunit.xml', getcwd() . '/phpunit.xml');
        $this->copyFile(__DIR__ . '/../assets/files/database.php', getcwd() . '/config/database.php');

        unlink(getcwd() . '/tests/ExampleTest.php');
        $composer = file_get_contents(getcwd() . '/composer.json');
        $newComposer = str_replace('"App\\": "app/",', '"App\\": "app/",' . PHP_EOL . '            "AppTest\\": "tests/"', $composer);
        file_put_contents(getcwd() . '/composer.json', $newComposer);

        $process = new Process('composer dump');
        $process->run();
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
        echo $process->getOutput();
    }
}
