<?php

declare(strict_types = 1);

namespace App\Console;

abstract class Command
{
    protected $name;

    protected $arguments;

    public function __construct()
    {
        $this->configure();
    }

    protected function setArgument(string $name)
    {
        $this->arguments[$name] = '';
    }

    public function getArgument(string $name)
    {
        return $this->arguments[$name];
    }

    abstract public function execute(): int;

    abstract protected function configure(): void;
}
