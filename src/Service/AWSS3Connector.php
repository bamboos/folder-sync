<?php

declare(strict_types = 1);

namespace App\Service;

class AWSS3Connector
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }
}
