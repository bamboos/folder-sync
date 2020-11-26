<?php

declare(strict_types = 1);

namespace App\Console;

use Throwable;

class CommandNotFoundException extends ConsoleException
{
    public function __construct(string $name)
    {
        parent::__construct("Command '{$name}' not found.");
    }
}
