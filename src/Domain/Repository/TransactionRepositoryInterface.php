<?php

declare(strict_types=1);

namespace CodePix\Bank\Domain\Repository;

use CodePix\Bank\Domain\Entities\Transaction;

interface TransactionRepositoryInterface
{
    public function registerDebit(Transaction $transaction): bool;

    public function save(Transaction $transaction): bool;

    public function find(string $id): ?Transaction;
}