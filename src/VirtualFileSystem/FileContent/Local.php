<?php

declare(strict_types = 1);

namespace App\VirtualFileSystem\FileContent;

use App\Service\FileManager;
use App\VirtualFileSystem\FileContent;

class Local implements FileContent
{
    private FileManager $fileManager;

    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    public function isAccessible(string $uri): bool
    {
        return $this->fileManager->fileExists($uri)
            && !$this->fileManager->isDirectory($uri);
    }

    public function retrieve(string $uri): string
    {
        return $this->fileManager->read($uri);
    }

    public function extractName($uri): string
    {
        return basename($uri);
    }
}
