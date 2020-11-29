<?php

declare(strict_types = 1);

namespace App\Service\AWS;

use App\Http\Client;
use App\Http\Request;

class AWSConnector
{
    private array $config;

    private Signature $signature;

    private Client $client;

    public function __construct(
        array $config,
        Signature $signature,
        Client $client
    ) {
        $this->config = $config;
        $this->signature = $signature;
        $this->client = $client;
    }

    public function deleteObject(string $resource): void
    {
        $request = new Request(
            $this->config['host'],
            $resource
        );
        $request->setMethod('DELETE');

        $this->signature->sign(
            $request,
            $this->config['credentials']['access_key_id'],
            $this->config['credentials']['secret_access_key']
        );

        $this->restCall($request);
    }

    public function putObject(string $resource, string $contents): void
    {
        $request = new Request(
            $this->config['host'],
            $resource,
            $contents,
        );
        $request->setMethod('PUT');

        $this->signature->sign(
            $request,
            $this->config['credentials']['access_key_id'],
            $this->config['credentials']['secret_access_key']
        );

        $this->restCall($request);
    }

    private function restCall(Request $request): void
    {
        $this->client->makeRequest($request);
    }
}
