<?php

declare(strict_types=1);

namespace CodePix\Bank\Domain\Events\Transaction;

use CodePix\Bank\Domain\Entities\Transaction;
use Costa\Entity\Contracts\EventInterface;

class CreateEvent implements EventInterface
{
    public function __construct(protected Transaction $transaction)
    {
    }

    public function payload(): array
    {
        return $this->transaction->toArray();
    }
}