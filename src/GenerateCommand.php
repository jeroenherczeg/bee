<?php

namespace Jeroenherczeg\Bee;

use Illuminate\Support\Str;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GenerateCommand
 * @package Jeroenherczeg\Bee
 */
class GenerateCommand extends AbstractCommand
{
    protected $input;

    protected $output;
    
    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('generate')->setDescription('Scaffold from a .bee file');
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

        //$this->init();

        //$this->loadConfig();

        $output->writeln('Let\'s get BEEzzzy!');
        $output->writeln($this->config);

        //if (!is_null($this->models)) {
        //    $this->createModels();
        //    $this->createMigrations();
        //}

        $output->writeln('<comment>Application ready! Build something amazing.</comment>');
    }
    //
    ///**
    // * Initialize
    // */
    //protected function init()
    //{
    //    $this->str = new Str();
    //    $this->projectDirectory = getcwd() . '/';
    //    $this->configFile = $this->projectDirectory . '.bee';
    //    $this->projectModelsDirectory = $this->projectDirectory . 'app/Models/Eloquent/';
    //    $this->projectMigrationsDirectory = $this->projectDirectory . 'database/migrations/';
    //}
    //
    ///**
    // * Read Config file
    // */
    //protected function loadConfig()
    //{
    //    if (!file_exists($this->configFile)) {
    //        throw new RuntimeException('No config file (.bee) found!');
    //    }
    //
    //    $contents = file_get_contents($this->configFile);
    //
    //    if (!$this->isValidJson($contents)) {
    //        throw new RuntimeException('The config file is not valid JSON!');
    //    }
    //
    //    $config = json_decode($contents);
    //
    //    if (isset($config->namespace)) {
    //        $this->namespace = $config->namespace;
    //    }
    //
    //    if (isset($config->models)) {
    //        $this->models = $config->models;
    //    }
    //}
    //
    ///**
    // * Create the models
    // */
    //protected function createModels()
    //{
    //    if (!file_exists($this->projectModelsDirectory)) {
    //        mkdir($this->projectModelsDirectory, 0755, true);
    //    }
    //
    //    $modelContents = file_get_contents($this->sourceDirectory . 'stubs/model.stub');
    //
    //    $modelContents = str_replace('{{namespace}}', $this->namespace, $modelContents);
    //
    //    foreach ($this->models as $model) {
    //        $data = str_replace('{{class}}', $model->name, $modelContents);
    //        $this->output->writeln('<info>Creating model ' . $model->name . '</info>');
    //        file_put_contents($this->projectModelsDirectory . ucfirst($model->name) . '.php', $data);
    //    }
    //}
    //
    ///**
    // * Create the migrations
    // */
    //public function createMigrations()
    //{
    //    if (!file_exists($this->projectMigrationsDirectory)) {
    //        mkdir($this->projectMigrationsDirectory, 0755, true);
    //    }
    //
    //    $migrationContents = file_get_contents($this->sourceDirectory . 'stubs/migration.stub');
    //
    //    foreach ($this->models as $model) {
    //        $data = str_replace('{{class}}', 'Create' . ucfirst($this->str->plural($model->name)) . 'Table', $migrationContents);
    //        $data = str_replace('{{table}}', $this->str->plural(strtolower($model->name)), $data);
    //
    //        $schema = '';
    //
    //        foreach ($model->columns as $column) {
    //            $schema .= '$table->string(\'' . $column->name .'\');' . PHP_EOL . '            ';
    //        }
    //
    //        $data = str_replace('{{schema_up}}', $schema, $data);
    //
    //        $this->output->writeln('<info>Creating migration for ' . $this->str->plural($model->name) . '</info>');
    //        file_put_contents($this->projectMigrationsDirectory . '2014_10_12_000000_create_' .$this->str->plural(strtolower($model->name)) . '_table.php', $data);
    //    }
    //}
    //
    //
    //
    ///**
    // * @param $contents
    // *
    // * @return bool
    // */
    //protected function isValidJson($contents) {
    //    json_decode($contents);
    //    return (json_last_error() === JSON_ERROR_NONE);
    //}
}
