<?php

declare(strict_types = 1);

namespace App\VirtualFileSystem;

class FileSystem
{
    private Directory $active;

    public function __construct()
    {
        $this->active = new Directory('/');
    }

    public function mkDir($name): void
    {
        $this->active->appendFile(new Directory($name));
    }

    public function rmFile($name): void
    {
        if ($file = $this->findByName($name)) {
            $this->active->removeFile($file);
        }
    }

    public function addFile(string $name, string $uri): void
    {
        $this->active->appendFile(new File($name, $uri));
    }

    public function getSortedContents(): Directory
    {
        return $this->active;
    }

    public function findByName(string $name): ?File
    {
        $list = $this->active->list();
        $result = array_search($name, $list);
        
        return $result !== false ? $list[$result] : null;
    }

    public function changeDir(string $name): void
    {
        if ($dir = $this->findByName($name) && !$dir->getUri()) {
            $this->active = $dir;
        }
    }
}
