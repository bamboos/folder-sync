<?php

declare(strict_types = 1);

namespace App\Service\AWS;

use App\Http\Request;

interface Signature
{
    public function sign(
        Request $request,
        string $accessKeyId,
        string $secretAccessKey
    ): void;
}
