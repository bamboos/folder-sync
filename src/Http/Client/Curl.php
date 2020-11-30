<?php

declare(strict_types = 1);

namespace App\Http\Client;

use App\Http\Client;
use App\Http\Request;
use App\Http\Response;
use App\Logger\Logger;

class Curl implements Client
{
    private Logger $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function makeRequest(Request $request): Response
    {
        foreach ($request->getHeaders() as $header => $value) {
            $headers[] = "{$header}: {$value}";
        }

        $ch = curl_init("https://{$request->getHost()}/{$request->getResource()}");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $request->getMethod(),
            CURLOPT_HTTPHEADER => $headers ?? [],
            CURLOPT_POSTFIELDS => $request->getBody()
        ]);

        $response = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($code !== 200 || $code !== 204) {
            $this->logger->log($response);
        }

        return new Response();
    }
}
