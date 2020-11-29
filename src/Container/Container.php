<?php

declare(strict_types = 1);

namespace App\Container;

class Container
{
    private array $services;

    public array $descs;

    public array $tagged;

    public function get(string $id)
    {
        return $this->services[$id] ?? $this->instantiate($id);
    }

    public function set(
        string $service,
        string $impl = null,
        array $params = [],
        string $tag = ''
    ): void {
        $this->descs[$service] = [
            'impl' => $impl ?? $service,
            'params' => array_map(
                fn ($param) => is_string($param) &&
                    (class_exists($param) || interface_exists($param))
                    ? fn () => $this->get($param)
                    : $param,
                $params
            )
        ];

        if ($tag) {
            $this->tagged[$tag][] = $service;
        }
    }

    private function instantiate(string $id)
    {
        if (!isset($this->descs[$id])) {
            throw new \RuntimeException("No service '{$id}'");
        }

        if (!class_exists($this->descs[$id]['impl'])) {
            throw new \RuntimeException('No implementation for service');
        }

        $this->services[$id] = new $this->descs[$id]['impl'](
            ...array_map(
                fn ($param) => is_callable($param) ? $param() : $param,
                $this->descs[$id]['params']
            )
        );

        return $this->services[$id];
    }

    public function getTagged(string $tag): array
    {
        return array_map(fn ($id) => $this->get($id), $this->tagged[$tag]);
    }
}
