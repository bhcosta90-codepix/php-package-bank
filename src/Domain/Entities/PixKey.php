<?php

declare(strict_types=1);

namespace CodePix\Bank\Domain\Entities;

use CodePix\Bank\Domain\Entities\Enum\PixKey\KindPixKey;
use Costa\Entity\Data;
use Costa\Entity\ValueObject\Uuid;

class PixKey extends Data
{
    public function __construct(
        protected string $reference,
        protected Uuid $bank,
        protected KindPixKey $kind,
        protected Account $account,
        protected string $key,
        protected bool $status = true,
    ) {
        parent::__construct();
    }
}