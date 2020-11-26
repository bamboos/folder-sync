<?php

declare(strict_types = 1);

namespace App\VirtualFileSystem\Backup;

use App\Service\AWSS3Connector;
use App\VirtualFileSystem\Backup;
use App\VirtualFileSystem\FileSystem;

class AWSS3 implements Backup
{
    private AWSS3Connector $connector;

    public function __construct(AWSS3Connector $connector)
    {
        $this->connector = $connector;
    }

    public function store(FileSystem $fileSystem): void
    {
        $this->connector;
    }
}
