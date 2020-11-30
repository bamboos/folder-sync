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

    public function append(string $name, string $contents): void
    {
        file_put_contents($name, $contents, FILE_APPEND);
    }

    public function fileExists(string $name): bool
    {
        return file_exists($name);
    }

    public function isDirectory(string $name): bool
    {
        return is_dir($name);
    }
}
