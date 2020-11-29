<?php

declare(strict_types = 1);

namespace App\VirtualFileSystem\Backup;

use App\Service\AWS\AWSConnector;
use App\VirtualFileSystem\Backup;
use App\VirtualFileSystem\FileSystem;

class AWSS3 implements Backup
{
    private AWSConnector $connector;

    public function __construct(AWSConnector $connector)
    {
        $this->connector = $connector;
    }

    public function store(FileSystem $fileSystem): void
    {
        $folder = 'vfs_backup_' . date('Y-m-d_H-i-s');
        $this->connector->putObject("{$folder}/", '');

        foreach ($fileSystem->traverse() as $file) {
            $this->connector->putObject($folder . $file->getPath(), $file->getContents());
        }
    }
}
