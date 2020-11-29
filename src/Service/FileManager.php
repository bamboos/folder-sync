<?php

declare(strict_types = 1);

namespace App\Service;

class FileManager
{
    public function read(string $name): string
    {
        return $this->fileExists($name)
            ? file_get_contents($name)
            : '';
    }

    public function write(string $name, string $contents): void
    {
        file_put_contents($name, $contents);
    }

    public function fileExists(string $name): bool
    {
        return file_exists($name);
    }
}
