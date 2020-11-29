<?php

declare(strict_types = 1);

namespace App\Http\Client;

use App\Http\Client;
use App\Http\Request;
use App\Http\Response;

class Curl implements Client
{
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
            CURLOPT_POSTFIELDS => $request->getBody(),
            CURLINFO_HEADER_OUT => true
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return new Response();
    }
}
