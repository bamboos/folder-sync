<?php

declare(strict_types = 1);

namespace App\VirtualFileSystem;

class FileSystem
{
    private Directory $active;

    private Directory $root;

    private FileContent $fileContent;

    public function __construct(FileContent $fileContent)
    {
        $this->root = new Directory('/');
        $this->active = $this->root;
        $this->active->setContainedAt($this->active);
        $this->fileContent = $fileContent;
    }

    public function mkDir($name): Directory
    {
        $dir = new Directory($name);
        $this->active->appendFile($dir);

        return $dir;
    }

    public function rmFile($name): void
    {
        if ($file = $this->findByName($name)) {
            $this->active->removeFile($file);
        }
    }

    public function addFile(string $uri): void
    {
        if ($this->fileContent->isAccessible($uri)) {
            $this->active->appendFile(
                new File($this->fileContent->extractName($uri), $uri)
            );
        }
    }

    public function getSortedContents(): array
    {
        $list = $this->active->list();

        usort(
            $list,
            fn($f1, $f2) => !$f1->getUri() . $f1->getName() <=>
                !$f2->getUri() . $f2->getName()
        );

        return $list;
    }

    public function findByName(string $name): ?File
    {
        return $this->active->list()[$name] ?? null;
    }

    public function changeDir(string $name): void
    {
        if ($name === '..') {
            $this->active = $this->active->getContainedAt();
            return;
        }

        if (($dir = $this->findByName($name)) && !$dir->getUri()) {
            $this->active = $dir;
        }
    }

    public function getRoot(): Directory
    {
        return $this->root;
    }

    public function setRoot(Directory $root): void
    {
        $this->root = $root;
        $this->active = $this->root;
    }

    public function getCurrentDir(): Directory
    {
        return $this->active;
    }

    private function getTraversedArray(Directory $directory = null): array
    {
        if ($directory === null) {
            $directory = $this->root;
            $name = '/';
        } else {
            $name = $directory->getName() . '/';
        }

        foreach ($directory->list() as $file) {
            $path = $name . $file->getName() . (!$file->getUri() ? '/' : '');

            if (!$file->getUri()) {
                $traversed = array_merge($traversed ?? [], array_map(
                    function($file) use ($directory, $name) {
                        $path = ($name ?? $directory->getName()) . $file->getPath();
                        $file->setPath($path);
                        return $file;
                    },
                    $this->getTraversedArray($file)
                ));
            }

            $file->setPath($path);
            $traversed[] = $file;
        }

        return $traversed ?? [];
    }

    public function traverse(): \Generator
    {
        foreach ($this->getTraversedArray() as $file) {
            if ($file->getUri()) {
                $file->setContents($this->getFileContents(
                    $file->getPath()
                ));
            }

            yield $file;
        }
    }

    public function getFileContents(string $filePath): string
    {
        $parts = array_filter(explode('/', $filePath));
        $active = $this->active;
        $this->active = $this->root;

        foreach ($parts as $name) {
            $file = $this->findByName($name);

            if (!$file) {
                break;
            }

            if (!$file->getUri()) {
                $this->changeDir($name);
            } else {
                return $this->fileContent->retrieve($file->getUri());
            }
        }

        $this->active = $active;

        return '';
    }
}
