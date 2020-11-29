<?php

declare(strict_types = 1);

namespace App\IO;

class TerminalOutput implements Output
{
    public function writeLine(string $line): void
    {
        echo $line . "\n";
    }

    public function write(string $line): void
    {
        echo $line;
    }
}
