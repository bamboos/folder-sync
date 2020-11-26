<?php

declare(strict_types = 1);

namespace App\IO;

class TerminalInput implements Input
{
    public function readLine(): string
    {
        $handle = fopen("php://stdin", "r");
        $input = trim(fgets($handle));
        fclose($handle);

        return $input;
    }
}
