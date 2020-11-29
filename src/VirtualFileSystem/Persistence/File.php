<?php

declare(strict_types = 1);

namespace App\VirtualFileSystem\Persistence;

use App\Service\FileManager;
use App\VirtualFileSystem\Directory;
use App\VirtualFileSystem\FileSystem;
use App\VirtualFileSystem\Persistence;

class File implements Persistence
{
    private FileManager $fileManager;

    private string $fileName;

    public function __construct(
        FileManager $fileManager,
        string $fileName
    ) {
        $this->fileManager = $fileManager;
        $this->fileName = $fileName;
    }

    public function load(): ?Directory
    {
        $contents = $this->fileManager->read($this->fileName);

        return $contents &&
            ($root = unserialize($contents)) instanceof Directory
            ? $root
            : null;
    }

    public function store(FileSystem $fileSystem): void
    {
        $this->fileManager->write(
            $this->fileName,
            serialize($fileSystem->getRoot())
        );
    }
}
