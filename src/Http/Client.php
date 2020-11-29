<?php

declare(strict_types = 1);

namespace App\Http;

interface Client
{
    public function makeRequest(Request $request): Response;
}
