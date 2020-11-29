<?php

declare(strict_types = 1);

namespace App\Service\AWS\Signature;

use App\Http\Request;
use App\Service\AWS\Signature;

class SignatureV4 implements Signature
{
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function sign(
        Request $request,
        string $accessKeyId,
        string $secretAccessKey
    ): void {
        $method = $request->getMethod();
        $resource = $request->getResource();
        $region = $this->config['region'];
        $service = $this->config['service'];
        $host = $request->getHost();
        $content = $request->getBody();
        $time = time();
        $longDate = gmdate('Ymd\THis\Z', $time);
        $shortDate = gmdate('Ymd', $time);
        $hashedPayload = hash('sha256', $content);
        $canonicalURI = "/{$resource}";
        $canonicalQuery = '';
        $canonicalHeaders =
            "host:{$host}\nx-amz-content-sha256:{$hashedPayload}\nx-amz-date:{$longDate}\n";
        $signedHeaders = 'host;x-amz-content-sha256;x-amz-date';

        $canonicalRequest =
            "{$method}\n" .
            "{$canonicalURI}\n" .
            "{$canonicalQuery}\n" .
            "{$canonicalHeaders}\n" .
            "{$signedHeaders}\n" .
            $hashedPayload;

        $hashedCanonicalRequest = hash('sha256', $canonicalRequest);
        $credentialScope = "{$shortDate}/{$region}/{$service}/aws4_request";

        $stringToSign =
            "AWS4-HMAC-SHA256\n" .
            "{$longDate}\n" .
            "{$credentialScope}\n" .
            $hashedCanonicalRequest;

        $signingKey = array_reduce([
            $shortDate,
            $region,
            $service,
            'aws4_request'
        ],
            fn ($signingKey, $value)
            => hash_hmac('sha256', $value, $signingKey, true),
            'AWS4' . $secretAccessKey
        );

        $signature = hash_hmac('sha256', $stringToSign, $signingKey);

        $authorizationHeader =
            "AWS4-HMAC-SHA256 " .
            "Credential={$accessKeyId}/{$credentialScope}, " .
            "SignedHeaders={$signedHeaders}, " .
            "Signature={$signature}";

        $request->addHeaders([
            'Authorization' => $authorizationHeader,
            'X-Amz-Date' => $longDate,
            'X-Amz-Content-Sha256' => $hashedPayload,
        ]);
    }
}
