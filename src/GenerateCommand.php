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
use Jeroenherczeg\Bee\Generators\Vue\ActionGenerator;
use Jeroenherczeg\Bee\Generators\Vue\ApiGenerator;
use Jeroenherczeg\Bee\Generators\Vue\GetterGenerator;
use Jeroenherczeg\Bee\Generators\Vue\MutationGenerator;
use Jeroenherczeg\Bee\Generators\Vue\StoreGenerator;
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

        $this->removeDefaultFiles();

        $this->runCommand('cp -R ' . __DIR__ . '/../files/* ' . getcwd());

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

        $this->runCommand('php artisan storage:link');

        (new ApiGenerator($data, $config, $output))->generate();
        (new ActionGenerator($data, $config, $output))->generate();
        (new MutationGenerator($data, $config, $output))->generate();
        (new GetterGenerator($data, $config, $output))->generate();
        (new StoreGenerator($data, $config, $output))->generate();

        //$this->runCommand('npm install');
        //
        //$this->runCommand('gulp');

        $output->writeln('<comment>And we are done!.</comment>');
    }
    
    public function configurePHPUnit()
    {
        $composer = file_get_contents(getcwd() . '/composer.json');
        $newComposer = str_replace('"App\\": "app/",', '"App\\": "app/",' . PHP_EOL . '            "AppTest\\": "tests/"', $composer);
        file_put_contents(getcwd() . '/composer.json', $newComposer);
    }

    public function removeDefaultFiles()
    {
        // Remove defaults

        unlink(getcwd() . '/database/factories/ModelFactory.php');

        unlink(getcwd() . '/database/migrations/2014_10_12_000000_create_users_table.php');
        unlink(getcwd() . '/database/migrations/2014_10_12_100000_create_password_resets_table.php');



        unlink(getcwd() . '/resources/assets/js/bootstrap.js');
        unlink(getcwd() . '/resources/assets/js/app.js');
        unlink(getcwd() . '/resources/assets/js/components/Example.vue');

        unlink(getcwd() . '/resources/assets/sass/app.scss');
        unlink(getcwd() . '/resources/assets/sass/_variables.scss');
        rmdir(getcwd() . '/resources/assets/sass/');

        unlink(getcwd() . '/resources/views/errors/503.blade.php');
        unlink(getcwd() . '/resources/views/vendor/.gitkeep');
        rmdir(getcwd() . '/resources/views/errors/');
        rmdir(getcwd() . '/resources/views/vendor/');

        unlink(getcwd() . '/tests/ExampleTest.php');

        unlink(getcwd() . '/app/User.php');

        unlink(getcwd() . '/readme.md');
    }
}
