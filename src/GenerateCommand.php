<?php

namespace Jeroenherczeg\Bee;

use Illuminate\Filesystem\Filesystem;
use Jeroenherczeg\Bee\Generators\Laravel\BaseGenerator;
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
use Jeroenherczeg\Bee\Generators\Vue\ConfigGenerator;
use Jeroenherczeg\Bee\Generators\Vue\GetterGenerator;
use Jeroenherczeg\Bee\Generators\Vue\MutationGenerator;
use Jeroenherczeg\Bee\Generators\Vue\StoreGenerator;
use Jeroenherczeg\Bee\ValueObjects\Config;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Class GenerateCommand
 * @package Jeroenherczeg\Bee
 */
class GenerateCommand extends Command
{
    /**
     * @var  OutputInterface
     */
    protected $output;

    /**
     * @var
     */
    protected $fs;
    
    /**
     * @var
     */
    protected $config;

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('generate')
             ->setDescription('Scaffold from a .bee scaffolding file');
    }

    /**
     * Initializes the command just after the input has been validated.
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $this->fs = new Filesystem();
        $this->config = new Config();
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
        $this->displayWelcome()
             ->removeDefaultBoilerplate()
             ->copyBaseFiles()
             ->generateCode()
             ->installDependencies()
             ->configureProject()
             ->runTests();

        $this->output->writeln(PHP_EOL . '<comment>Good luck and have fun!</comment>');
    }

    private function displayWelcome()
    {


        $this->output->writeln('');
        $this->output->writeln('                ▄████▄');
        $this->output->writeln('       ▄████▄ ▄██░░░███▄');
        $this->output->writeln('     ▄██░░░░███░░░░░░░░██▄');
        $this->output->writeln('     █░░░░░░░░██░░░░░░░░░██▄');
        $this->output->writeln('     █░░░░░░░░░██░░░░░░░░░██');
        $this->output->writeln('     █░░░░░░░░░░██░░░░░░░░░█');
        $this->output->writeln('     █░░░░░░░░░░░█░░░░░░░░░█');
        $this->output->writeln('     █░░░░░░░░░░░█░░░░░░░░██');
        $this->output->writeln('     █░░░░░░░░░░░██░░░░░░░█');
        $this->output->writeln('     █░░░░░░░░░░░░█░░░░░░█');
        $this->output->writeln('     █░░░░░░░░░░░░█░░░░░██');
        $this->output->writeln('      █░░░░░░░░░░░█░░░░██  █        █');
        $this->output->writeln('       ██░░░░░░░░░█░░░░█   █        █');
        $this->output->writeln('        ███░░░░░░░█░░░░█    █      █');
        $this->output->writeln('         █████░░░░█░░░███▄   █    █');
        $this->output->writeln('       ███▒▒▒██████████▒▒█ ▄████████▄');
        $this->output->writeln('      ██▒▒▒▒███▒▒▒████▒▒▒███░░░░░░░░█');
        $this->output->writeln('     ▄█▒▒▒▒███▒▒▒▒███▒▒▒▒▒█░░░█░░█░░░█');
        $this->output->writeln('    ███▒▒▒▒██▒▒▒▒▒██▒▒▒▒▒▒█░░░░░░░░░░█');
        $this->output->writeln('     ▀█▒▒▒▒██▒▒▒▒▒██▒▒▒▒▒▒█░░█░░░░█░░█');
        $this->output->writeln('      ██▒▒▒███▒▒▒▒███▒▒▒▒▒█░░░████░░██');
        $this->output->writeln('       ██▒▒▒███▒▒▒████▒▒▒▒███░░░░░░██');
        $this->output->writeln('        ██▒▒▒███▒▒▒████▒▒██ ▀▀▀▀▀▀▀▀');
        $this->output->writeln('         ▀███████████████▀');
        $this->output->writeln('');
        $this->output->writeln('          Let\'s get to work!');

        return $this;
    }

    /**
     * Remove default files
     */
    private function removeDefaultBoilerplate()
    {
        $this->output->writeln(PHP_EOL . '<comment>Removing default boilerplate ...</comment>');

        $defaultBoilerplate = [
            'database/factories/ModelFactory.php',
            'database/migrations/2014_10_12_000000_create_users_table.php',
            'database/migrations/2014_10_12_100000_create_password_resets_table.php',
            'resources/assets/js/bootstrap.js',
            'resources/assets/js/app.js',
            'resources/assets/js/components/Example.vue',
            'resources/assets/sass/',
            'resources/views/errors/',
            'resources/views/vendor/',
            'resources/views/welcome.blade.php',
            'tests/ExampleTest.php',
            'app/User.php',
            'readme.md',
        ];

        foreach ($defaultBoilerplate as $boilerplate) {
            $boilerplateFullPath = $this->config->getProjectDir() . '/' . $boilerplate;

            if (!$this->fs->exists($boilerplateFullPath)) {
                $this->output->writeln(' ✗ <error>' . $boilerplate . '</error> doesn\'t exists! ');
                continue;
            }

            if ($this->fs->isDirectory($boilerplateFullPath)) {
                $this->fs->deleteDirectory($boilerplateFullPath);
            } else {
                $this->fs->delete($boilerplateFullPath);
            }

            $this->output->writeln('<info> ✓ Removed ' . $boilerplate . '</info>');
        }

        return $this;
    }

    /**
     * Copy base files
     */
    private function copyBaseFiles()
    {
        $this->output->writeln(PHP_EOL . '<comment>Copying base files ...</comment>');

        $files = $this->fs->allFiles($this->config->getBaseFilesDir(), true);

        foreach ($files as $file) {
            $fileFullPath = $file->getFilename();

            if ($file->getRelativePath() != '') {
                $fileFullPath = $file->getRelativePath() . '/' . $fileFullPath;
                if (!$this->fs->isDirectory($this->config->getProjectDir() . '/' . $file->getRelativePath())) {
                    $this->fs->makeDirectory($this->config->getProjectDir() . '/' . $file->getRelativePath(), 0755, true);
                }
            }

            $this->fs->copy($this->config->getBaseFilesDir() . $fileFullPath, $this->config->getProjectDir() . '/' . $fileFullPath);
            $this->output->writeln('<info> ✓ Copied ' . $fileFullPath . '</info>');
        }

        return $this;
    }

    /**
     * Generating code
     */
    private function generateCode()
    {
        $this->output->writeln(PHP_EOL . '<comment>Generating code ...</comment>');

        $results['laravel base']         = (new BaseGenerator())->generate()->getResults();
        $results['laravel migrations']   = (new MigrationGenerator())->generate()->getResults();
        $results['laravel models']       = (new ModelGenerator())->generate()->getResults();
        $results['laravel factories']    = (new FactoryGenerator())->generate()->getResults();
        $results['laravel seeds']        = (new SeedGenerator())->generate()->getResults();
        $results['laravel transformers'] = (new TransformerGenerator())->generate()->getResults();
        $results['laravel requests']     = (new RequestGenerator())->generate()->getResults();
        $results['laravel controllers']  = (new ControllerGenerator())->generate()->getResults();
        $results['laravel routes']       = (new RoutesGenerator())->generate()->getResults();
        $results['laravel tests']        = (new TestGenerator())->generate()->getResults();

        $results['vue api']              = (new ApiGenerator())->generate()->getResults();
        $results['vue actions']          = (new ActionGenerator())->generate()->getResults();
        $results['vue getters']          = (new GetterGenerator())->generate()->getResults();
        $results['vue mutations']        = (new MutationGenerator())->generate()->getResults();
        $results['vue store']            = (new StoreGenerator())->generate()->getResults();
        $results['vue config']           = (new ConfigGenerator())->generate()->getResults();

        foreach ($results as $generator => $filenames) {
            $this->output->writeln(PHP_EOL . ' ➝ Generated ' . $generator);
            foreach ($filenames as $filename ) {
                $this->output->writeln('<info>   ✶ ' . $filename . '</info>');
            }
        }

        return $this;
    }

    /**
     * Installing dependencies
     */
    private function installDependencies()
    {
        $this->output->writeln(PHP_EOL . '<comment>Installing dependencies ...</comment>');

        $this->runCommand('composer require league/fractal');
        $this->output->writeln('<info> ✓ Installed Fractal</info>');

        $this->runCommand('npm install');
        $this->output->writeln('<info> ✓ Installed NPM Packages</info>');

        return $this;
    }

    /**
     * Configuring the project
     */
    private function configureProject()
    {
        $this->output->writeln(PHP_EOL . '<comment>Configuring the project ...</comment>');

        $composer = file_get_contents(getcwd() . '/composer.json');
        $newComposer = str_replace('"App\\": "app/",', '"App\\": "app/",' . PHP_EOL . '            "AppTest\\": "tests/"', $composer);
        file_put_contents(getcwd() . '/composer.json', $newComposer);
        $this->output->writeln('<info> ✓ Added composer autoloading for test namespace</info>');

        $this->runCommand('php artisan storage:link');
        $this->output->writeln('<info> ✓ Linked storage to public</info>');

        // --- TEMP FIX ---
        $this->runCommand('npm i webpack@2.1.0-beta.22');
        // --- TEMP FIX ---

        $this->runCommand('gulp');
        $this->output->writeln('<info> ✓ Runned gulp for webpack and less compilation</info>');

        return $this;
    }

    /**
     * Running tests
     */
    private function runTests()
    {
        $this->output->writeln(PHP_EOL . '<comment>Running tests ...</comment>');

        $output = $this->runCommand('./vendor/bin/phpunit');
        $this->output->writeln(PHP_EOL . $output);

        return $this;
    }

    private function runCommand($command)
    {
        $process = new Process($command);
        $process->setTimeout(3600);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $process->getOutput();
    }
}
