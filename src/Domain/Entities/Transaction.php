<?php

declare(strict_types=1);

namespace CodePix\Bank\Domain\Entities;

use CodePix\Bank\Domain\Entities\Enum\Transaction\StatusTransaction;
use CodePix\Bank\Domain\Events\Transaction\ConfirmedEvent;
use CodePix\Bank\Domain\Events\Transaction\CreateEvent;
use Costa\Entity\Data;
use Costa\Entity\ValueObject\Uuid;

class Transaction extends Data
{
    public function __construct(
        protected Account $accountFrom,
        protected float $value,
        protected PixKey $pixKeyTo,
        protected string $description,
        protected StatusTransaction $status = StatusTransaction::PENDING,
        protected ?Uuid $debit = null,
        protected ?string $cancelDescription = null,
    ) {
        parent::__construct();
        if ($this->debit) {
            $this->addEvent(new CreateEvent($this));
        } else {
            $this->addEvent(new ConfirmedEvent($this));
        }
    }
}