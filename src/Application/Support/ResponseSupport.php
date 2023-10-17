<?php

declare(strict_types=1);

namespace CodePix\Bank\Application\Support;

class ResponseSupport
{
    public function __construct(
        public int $status,
        public string $id,
        public ?array $response,
        public ?string $error = null
    ) {
    }
}