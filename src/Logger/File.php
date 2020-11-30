<?php

declare(strict_types = 1);

namespace App\Logger;

use App\Service\FileManager;

class File implements Logger
{
    private string $fileName;

    private FileManager $fileManager;

    public function __construct(
        string $fileName,
        FileManager $fileManager
    ) {
        $this->fileName = $fileName;
        $this->fileManager = $fileManager;
    }

    public function log(string $data): void
    {
        $this->fileManager->append($this->fileName, $data . "\n\n");
    }
}
