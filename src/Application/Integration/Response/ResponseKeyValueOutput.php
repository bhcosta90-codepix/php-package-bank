<?php

declare(strict_types=1);

namespace CodePix\Bank\Application\Integration\Response;

class ResponseKeyValueOutput
{
    public function __construct(public string $id, public string $key, public int $status)
    {
        //
    }
}