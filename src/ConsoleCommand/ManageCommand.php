<?php

declare(strict_types = 1);

namespace App\ConsoleCommand;

use App\Console\InteractiveCommand;
use App\IO\Input;
use App\IO\Output;

class ManageCommand extends InteractiveCommand
{
    protected string $name = 'manage';

    public function execute(Input $input, Output $output): int
    {
        $this->getRunner()->run(['cd', null]);

        return parent::execute($input, $output);
    }

    protected function configure(): void
    {
        $this->addCommand('cd');
    }
}
