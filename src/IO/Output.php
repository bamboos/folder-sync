<?php

declare(strict_types = 1);

namespace App\IO;

interface Output
{
    public function writeLine(string $line): void;

    public function write(string $line): void;
}
