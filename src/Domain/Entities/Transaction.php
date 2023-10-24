<?php

declare(strict_types=1);

namespace CodePix\Bank\Domain\Entities;

use CodePix\Bank\Domain\Entities\Enum\Transaction\StatusTransaction;
use CodePix\Bank\Domain\Events\Transaction\CreateEvent;
use Costa\Entity\Data;

class Transaction extends Data
{
    public function __construct(
        protected Account $accountFrom,
        protected float $value,
        protected PixKey $pixKeyTo,
        protected string $description,
        protected StatusTransaction $status = StatusTransaction::PENDING,
        protected ?string $cancelDescription = null,
    ) {
        parent::__construct();
        $this->addEvent(new CreateEvent($this));
    }
}