<?php

declare(strict_types=1);

namespace CodePix\Bank\Application\Integration;

interface PixKeyIntegrationInterface
{
    public function register(
        string $bank,
        string $account,
        string $kind,
        ?string $key
    ): Response\ResponseKeyValueOutput;
}