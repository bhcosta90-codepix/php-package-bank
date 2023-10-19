<?php

declare(strict_types=1);

namespace CodePix\Bank\Application\Integration\DTO;

class ResponseKeyValueOutput
{
    public function __construct(public string $id, public string $key)
    {
        //
    }
}