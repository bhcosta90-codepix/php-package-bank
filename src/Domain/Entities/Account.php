<?php

declare(strict_types=1);

namespace CodePix\Bank\Domain\Entities;

use Costa\Entity\Data;
use Costa\Entity\ValueObject\Uuid;

class Account extends Data
{
    public function __construct(
        protected string $reference,
        protected string $name,
        protected Uuid $bank,
        protected Uuid $agency,
        protected string $number,
        /**
         * @var PixKey[]
         */
        protected array $pixKeys = [],
    ) {
        parent::__construct();
    }
}