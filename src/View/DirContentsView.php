<?php

declare(strict_types = 1);

namespace App\View;

use App\VirtualFileSystem\FileSystem;

class DirContentsView
{
    public function display(FileSystem $fileSystem): void
    {
        $directory = $fileSystem->getCurrentDir();
        echo '' . $directory->getName() . "\n";

        if ($directory->getName() !== '/') {
            echo "  |- ..\n";
        }

        foreach ($fileSystem->getSortedContents() as $file) {
            $name = $file->getName() . ($file->getUri() ? " ({$file->getUri()})" : '');
            echo '  |' . (!$file->getUri() && $file->list() ? '+' : '-') . ' ' . $name . "\n";
        }
    }
}
