<?php

namespace Jeroenherczeg\Bee;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

abstract class AbstractCommand extends Command
{


    ///**
    // * Load the configuration file
    // */
    //protected function loadConfig()
    //{
    //
    //}
    //
    ///**
    // * Load the configuration file
    // */
    //protected function loadScaffold()
    //{
    //    $file = '.bee';
    //    $path = getcwd() . '/';
    //
    //    return $this->loadJson($file, $path);
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
    //
    //    return (json_last_error() === JSON_ERROR_NONE);
    //}
    //
    ///**
    // * @param $source
    // * @param $destination
    // */
    //protected function copyFile($source, $destination)
    //{
    //    if (!copy($source, $destination)) {
    //        echo "failed to copy $source...\n";
    //    }
    //}
    //
    ///**
    // * @param $command
    // */
    //protected function runCommand($command)
    //{
    //    $process = new Process($command);
    //    $process->run();
    //    if (!$process->isSuccessful()) {
    //        throw new ProcessFailedException($process);
    //    }
    //    echo $process->getOutput();
    //}
}
