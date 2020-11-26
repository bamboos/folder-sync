<?php

declare(strict_types = 1);

namespace App\VirtualFileSystem;

interface Persistence
{
    public function load(): FileSystem;

    public function store(FileSystem $fileSystem): void;
}
