<?php

declare(strict_types=1);

namespace CodePix\Bank\Domain\Repository;

use BRCas\CA\Responses\PaginationResponse;
use CodePix\Bank\Domain\Entities\Transaction;

interface TransactionRepositoryInterface
{
    public function registerDebit(Transaction $transaction): bool;

    public function registerCredit(Transaction $transaction): bool;

    public function save(Transaction $transaction): bool;

    public function find(string $id): ?Transaction;

    public function getAll(string $account): PaginationResponse;
}