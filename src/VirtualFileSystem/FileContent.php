<?php

declare(strict_types = 1);

namespace App\VirtualFileSystem;

interface FileContent
{
    public function isAccessible(string $uri): bool;

    public function retrieve(string $uri): string;

    public function extractName($uri): string;
}
