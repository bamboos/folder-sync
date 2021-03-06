<?php

declare(strict_types = 1);

namespace App\VirtualFileSystem;

class File
{
    private string $name;

    private ?string $uri;

    private Directory $containedAt;

    private string $path;

    private string $contents = '';

    public function __construct(string $name, string $uri = null)
    {
        $this->name = $name;
        $this->uri = $uri;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUri(): ?string
    {
        return $this->uri;
    }

    public function setContainedAt(Directory $directory): void
    {
        $this->containedAt = $directory;
    }

    public function getContainedAt(): Directory
    {
        return $this->containedAt;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getContents(): string
    {
        return $this->contents;
    }

    public function setContents(string $contents): void
    {
        $this->contents = $contents;
    }
}
