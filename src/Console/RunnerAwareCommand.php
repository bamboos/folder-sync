<?php

namespace App\Console;

trait RunnerAwareCommand
{
    private CommandRunner $runner;

    public function getRunner(): CommandRunner
    {
        return $this->runner;
    }

    public function setRunner(CommandRunner $runner): void
    {
        $this->runner = $runner;
    }
}
