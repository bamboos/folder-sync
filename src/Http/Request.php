<?php

declare(strict_types = 1);

namespace App\Http;

class Request
{
    private string $method;

    private array $headers = [];

    private string $host;

    private string $resource;

    private string $body;

    public function __construct(
        string $host,
        string $resource,
        string $body = ''
    ) {
        $this->host = $host;
        $this->resource = $resource;
        $this->body = $body;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $name)
    {
        $this->method = $name;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function addHeaders(array $headers): void
    {
        $this->headers += $headers;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getResource(): string
    {
        return $this->resource;
    }

    public function getBody(): string
    {
        return $this->body;
    }
}
