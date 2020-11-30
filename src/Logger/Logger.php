<?php

declare(strict_types = 1);

namespace App\Logger;

interface Logger
{
    public function log(string $data): void;
}
