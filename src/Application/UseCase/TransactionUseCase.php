<?php

declare(strict_types=1);

namespace CodePix\Bank\Application\UseCase;

use CodePix\Bank\Application\Exception\NotFoundException;
use CodePix\Bank\Application\Exception\UseCaseException;
use CodePix\Bank\Application\integration\TransactionIntegrationInterface;
use CodePix\Bank\Domain\Entities\Transaction;
use CodePix\Bank\Domain\Repository\PixKeyRepositoryInterface;
use CodePix\Bank\Domain\Repository\TransactionRepositoryInterface;

class TransactionUseCase
{
    public function __construct(
        protected PixKeyRepositoryInterface $pixKeyRepository,
        protected TransactionRepositoryInterface $transactionRepository,
    ) {
        //
    }

    /**
     * @throws NotFoundException
     * @throws UseCaseException
     */
    public function register(string $account, float $value, string $kind, string $key, string $description): Transaction
    {
        if (!$account = $this->pixKeyRepository->findAccount($account)) {
            throw new NotFoundException('Account not found');
        }

        if (!$pix = $this->pixKeyRepository->findKeyByKind($kind, $key)) {
            throw new NotFoundException('Pix not found');
        }

        $transaction = new Transaction(
            accountFrom: $account,
            value: $value,
            pixKeyTo: $pix,
            description: $description,
        );

        $response = $this->transactionRepository->register($transaction);

        if (!$response) {
            throw new UseCaseException();
        }

        return $transaction;
    }

    /**
     * @throws NotFoundException
     * @throws UseCaseException
     */
    public function confirm(string $id): Transaction
    {
        if (!$transaction = $this->transactionRepository->find($id)) {
            throw new NotFoundException('Transaction not found');
        }

        $transaction->confirmed();
        $response = $this->transactionRepository->save($transaction);

        if (!$response) {
            throw new UseCaseException();
        }

        return $transaction;
    }

    /**
     * @throws NotFoundException
     * @throws UseCaseException
     */
    public function complete(string $id): Transaction
    {
        if (!$transaction = $this->transactionRepository->find($id)) {
            throw new NotFoundException('Transaction not found');
        }

        $transaction->complete();
        $response = $this->transactionRepository->save($transaction);

        if (!$response) {
            throw new UseCaseException();
        }

        return $transaction;
    }

    /**
     * @throws NotFoundException
     * @throws UseCaseException
     */
    public function error(string $id, string $description): Transaction
    {
        if (!$transaction = $this->transactionRepository->find($id)) {
            throw new NotFoundException('Transaction not found');
        }

        $transaction->error($description);
        $response = $this->transactionRepository->save($transaction);

        if (!$response) {
            throw new UseCaseException();
        }

        return $transaction;
    }
}