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
        protected string $name,
        protected Uuid $bank,
        protected Uuid $agency,
        protected string $number,
        protected Password $password,
    ) {
        parent::__construct();
    }

    public function credit(float $value): void
    {
        $this->balance += $value;
    }

    public function debit(float $value): void
    {
        $this->balance -= $value;
    }
}