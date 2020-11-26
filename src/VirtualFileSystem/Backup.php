<?php

declare(strict_types = 1);

namespace App\VirtualFileSystem;

interface Backup
{
    public function store(FileSystem $fileSystem): void;
}
