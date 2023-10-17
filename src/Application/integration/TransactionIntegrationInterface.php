<?php

declare(strict_types=1);

namespace CodePix\Bank\Application\integration;

use CodePix\Bank\Application\Support\ResponseSupport;

interface TransactionIntegrationInterface
{
    public function register(
        string $account,
        float $value,
        string $kind,
        string $key,
        string $description
    ): ResponseSupport;
}