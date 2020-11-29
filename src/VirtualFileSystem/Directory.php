<?php

declare(strict_types = 1);

namespace App\VirtualFileSystem;

class Directory extends File
{
    private array $files = [];

    public function __construct(string $name)
    {
        parent::__construct($name);
    }

    public function appendFile(File $file)
    {
        $file->setContainedAt($this);
        $this->files[$file->getName()] = $file;
    }

    public function removeFile(File $file)
    {
        unset($this->files[$file->getName()]);
    }

    public function list(): array
    {
        return $this->files;
    }
}
