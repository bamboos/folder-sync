<?php

namespace App\Console;

use App\IO\Input;
use App\IO\Output;

abstract class InteractiveCommand extends Command
{
    use RunnerAwareCommand;

    private array $commands;

    public function execute(Input $input, Output $output): int
    {
        while (true) {
            $args = array_filter(explode(' ', $input->readLine()));

            if (!$args) {
                continue;
            }

            if (!isset($this->commands[$args[0]])) {
                $output->writeLine('Command not registered within parent\'s scope.');
                continue;
            }

            try {
                $this->runner->run($args);
            } catch (ConsoleException $e) {
                $output->writeLine($e->getMessage());
            }
        };

        return 0;
    }

    abstract protected function configure(): void;

    protected function addCommand(string $name)
    {
        $this->commands[$name] = 1;
    }
}
