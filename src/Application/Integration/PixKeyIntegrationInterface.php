<?php

declare(strict_types=1);

namespace CodePix\Bank\Application\Integration;

use CodePix\Bank\Application\Support\ResponseSupport;

interface PixKeyIntegrationInterface
{
    public function register(string $bank, string $account, string $kind, string $key): ResponseSupport;
}