<?php

declare(strict_types=1);

namespace CodePix\Bank\Application\integration;

use CodePix\Bank\Application\Support\ResponseSupport;

interface PixKeyIntegrationInterface
{
    public function register(string $bank, string $kind, string $key): ResponseSupport;

    public function addAccount(string $bank, string $name, string $agency, string $number): ResponseSupport;
}