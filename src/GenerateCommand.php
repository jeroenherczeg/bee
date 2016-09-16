<?php

namespace Jeroenherczeg\Bee;

use Jeroenherczeg\Bee\Generators\Laravel\ControllerGenerator;
use Jeroenherczeg\Bee\Generators\Laravel\FactoryGenerator;
use Jeroenherczeg\Bee\Generators\Laravel\MigrationGenerator;
use Jeroenherczeg\Bee\Generators\Laravel\ModelGenerator;
use Jeroenherczeg\Bee\Generators\Laravel\RequestGenerator;
use Jeroenherczeg\Bee\Generators\Laravel\RoutesGenerator;
use Jeroenherczeg\Bee\Generators\Laravel\SeedGenerator;
use Jeroenherczeg\Bee\Generators\Laravel\TestGenerator;
use Jeroenherczeg\Bee\Generators\Laravel\TransformerGenerator;
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
         * LARAVEL
         */

        $this->runCommand('composer require league/fractal');

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

        $this->runCommand('composer dump');

        $this->runCommand('./vendor/bin/phpunit');

        /**
         * VUE
         */

        $this->installVue();

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
    }

    public function installVue()
    {
        // Remove defaults
        unlink(getcwd() . '/resources/assets/js/bootstrap.js');
        unlink(getcwd() . '/resources/assets/js/app.js');
        unlink(getcwd() . '/resources/assets/js/components/Example.vue');

        // Install

        mkdir(getcwd() . '/resources/assets/js/views', 0755, true);

        $this->copyFile(__DIR__ . '/../assets/js/app.js', getcwd() . '/resources/assets/js/app.js');
        $this->copyFile(__DIR__ . '/../assets/js/App.vue', getcwd() . '/resources/assets/js/App.vue');
        $this->copyFile(__DIR__ . '/../assets/js/views/Home.vue', getcwd() . '/resources/assets/js/views/Home.vue');
        $this->copyFile(__DIR__ . '/../assets/js/views/NotFound.vue', getcwd() . '/resources/assets/js/views/NotFound.vue');

        $this->copyFile(__DIR__ . '/../assets/files/package.json', getcwd() . '/package.json');

        $this->runCommand('npm install');

        $this->runCommand('gulp');
    }
}
