<?php

declare(strict_types = 1);

namespace App\Console;

use App\IO\Input;
use App\IO\Output;

abstract class Command
{
    protected string $name;

    protected array $arguments = [];

    public function __construct()
    {
        $this->configure();
    }

    protected function setArgument(string $name): void
    {
        $this->arguments[$name] = '';
    }

    public function getArgument(string $name)
    {
        return $this->arguments[$name];
    }

    abstract public function execute(Input $input, Output $output): int;

    abstract protected function configure(): void;
}
