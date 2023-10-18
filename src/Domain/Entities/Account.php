<?php

declare(strict_types=1);

namespace CodePix\Bank\Domain\Entities;

use BRCas\CA\ValueObject\Password;
use Costa\Entity\Data;
use Costa\Entity\ValueObject\Uuid;

class Account extends Data
{
    protected float $balance = 0;

    /**
     * @var PixKey[]
     */
    protected array $pixKeys = [];

    public function __construct(
        protected string $reference,
        protected string $name,
        protected Uuid $bank,
        protected string $agency,
        protected string $number,
        protected Password $password,
    ) {
        parent::__construct();
    }
}