<?php

declare(strict_types=1);

namespace CodePix\Bank\Domain\Entities;

use CodePix\Bank\Domain\Entities\Enum\PixKey\KindPixKey;
use CodePix\Bank\Domain\Entities\Enum\Transaction\StatusTransaction;
use CodePix\Bank\Domain\Events\Transaction\CompletedEvent;
use CodePix\Bank\Domain\Events\Transaction\ConfirmedEvent;
use CodePix\Bank\Domain\Events\Transaction\CreateEvent;
use Costa\Entity\Data;
use Costa\Entity\ValueObject\Uuid;

class Transaction extends Data
{
    public function __construct(
        protected Account $accountFrom,
        protected float $value,
        protected KindPixKey $kind,
        protected string $key,
        protected string $description,
        protected StatusTransaction $status = StatusTransaction::PENDING,
        protected ?Uuid $debit = null,
        protected ?string $cancelDescription = null,
    ) {
        parent::__construct();
        if (empty($this->debit)) {
            $this->addEvent(new CreateEvent($this));
        } else {
            $this->addEvent(new ConfirmedEvent($this));
        }
    }

    public function completed(): void
    {
        $this->status = StatusTransaction::COMPLETED;

        if (empty($this->debit)) {
            $this->accountFrom->debit($this->value);
            $this->addEvent(new CompletedEvent($this));
        } else {
            $this->accountFrom->credit($this->value);
        }
    }
}