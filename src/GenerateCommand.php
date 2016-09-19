<?php

namespace Jeroenherczeg\Bee;

use Illuminate\Filesystem\Filesystem;
use Jeroenherczeg\Bee\Generators\Laravel\ModelGenerator;
use Jeroenherczeg\Bee\ValueObject\Config;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
        $this->output->writeln('Let\'s get to work!');

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
            'tests/ExampleTest.php',
            'app/User.php',
            'readme.md',
        ];

        foreach ($defaultBoilerplate as $boilerplate) {
            $boilerplateFullPath = $this->config->getProjectDir() . '/' . $boilerplate;

            if (!$this->fs->exists($boilerplateFullPath)) {
                $this->output->writeln(' - <error>' . $boilerplate . '</error> doesn\'t exists! ');
                continue;
            }

            $this->fs->delete($boilerplateFullPath);
            $this->output->writeln('<info> - Removed ' . $boilerplate . '</info>');
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
            }

            $this->fs->copy($this->config->getBaseFilesDir() . $fileFullPath, $this->config->getProjectDir() . '/' . $fileFullPath);
            $this->output->writeln('<info> - Copied ' . $fileFullPath . '</info>');
        }

        return $this;
    }

    /**
     * Generating code
     */
    private function generateCode()
    {
        $this->output->writeln(PHP_EOL . '<comment>Generating code ...</comment>');
        
        $results = (new ModelGenerator())->generate()->getResults();

        foreach ($results as $result) {
            $this->output->writeln('<info> - Generated ' . $result . '</info>');
        }

        return $this;
    }

    /**
     * Installing dependencies
     */
    private function installDependencies()
    {
        $this->output->writeln(PHP_EOL . '<comment>Installing dependencies ...</comment>');

        //$composer = file_get_contents(getcwd() . '/composer.json');
        //$newComposer = str_replace('"App\\": "app/",', '"App\\": "app/",' . PHP_EOL . '            "AppTest\\": "tests/"', $composer);
        //file_put_contents(getcwd() . '/composer.json', $newComposer);
        //
        //$this->runCommand('composer require league/fractal');
        //
        //$this->runCommand('composer require laravel/passport');
        //
        //$this->runCommand('php artisan storage:link');
        //
        //$this->runCommand('npm install');
        //
        //$this->runCommand('gulp');

        return $this;
    }

    /**
     * Running tests
     */
    private function runTests()
    {
        $this->output->writeln(PHP_EOL . '<comment>Running tests ...</comment>');
        //
        //$this->runCommand('./vendor/bin/phpunit');
        //
        return $this;
    }

}
