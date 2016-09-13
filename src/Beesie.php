<?php

namespace Jeroenherczeg\Bee;

use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Beesie extends Command
{
    protected $input;

    protected $output;

    protected $namespace = 'App';

    protected $models = null;

    protected $sourceDirectory = '/Users/jeroen/.composer/vendor/jeroenherczeg/bee/src/';

    protected $projectDirectory;

    protected $configFile;

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('generate')->setDescription('Generate a bee');
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

        $this->init();

        $this->loadConfig();

        $output->writeln('<info>Namespace ' . $this->namespace . '...</info>');

        if (!is_null($this->models)) {
            $this->createModels();
        }

        $output->writeln('<comment>Application ready! Build something amazing.</comment>');
    }


    protected function init()
    {
        $this->projectDirectory = getcwd() . '/';
        $this->configFile = $this->projectDirectory . '.bee';
    }

    /**
     * Read Config file
     */
    protected function loadConfig()
    {
        if (!file_exists($this->configFile)) {
            throw new RuntimeException('No config file (.bee) found!');
        }

        $contents = file_get_contents($this->configFile);

        if (!$this->isValidJson($contents)) {
            throw new RuntimeException('The config file is not valid JSON!');
        }

        $config = json_decode($contents);

        if (isset($config->namespace)) {
            $this->namespace = $config->namespace;
        }

        if (isset($config->models)) {
            $this->models = $config->models;
        }
    }

    protected function createModels()
    {
        $modelContents = file_get_contents($this->sourceDirectory . 'stubs/model.stub');

        $modelContents = str_replace('{{namespace}}', $this->namespace, $modelContents);

        foreach ($this->models as $model) {
            $data = str_replace('{{class}}', $model->name, $modelContents);
            $this->output->writeln('<info>Creating model ' . $model->name . '</info>');
            file_put_contents($this->projectDirectory . 'app/'. ucfirst($model->name) . '.php', $data);

        }
    }

    /**
     * @param $contents
     *
     * @return bool
     */
    protected function isValidJson($contents) {
        json_decode($contents);
        return (json_last_error() === JSON_ERROR_NONE);
    }
}
