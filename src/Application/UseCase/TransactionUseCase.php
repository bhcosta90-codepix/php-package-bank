<?php

declare(strict_types=1);

namespace CodePix\Bank\Application\UseCase;

use BRCas\CA\Contracts\Event\EventManagerInterface;
use CodePix\Bank\Application\Exception\NotFoundException;
use CodePix\Bank\Application\Exception\UseCaseException;
use CodePix\Bank\Application\integration\TransactionIntegrationInterface;
use CodePix\Bank\Domain\Entities\Transaction;
use CodePix\Bank\Domain\Repository\PixKeyRepositoryInterface;
use CodePix\Bank\Domain\Repository\TransactionRepositoryInterface;
use Costa\Entity\Exceptions\NotificationException;

class TransactionUseCase
{
    public function __construct(
        protected PixKeyRepositoryInterface $pixKeyRepository,
        protected TransactionRepositoryInterface $transactionRepository,
        protected EventManagerInterface $eventManager,
    ) {
        //
    }

    /**
     * @throws NotFoundException
     * @throws NotificationException
     * @throws UseCaseException
     */
    public function registerDebit(string $account, float $value, string $kind, string $key, string $description): Transaction
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

        $response = $this->transactionRepository->registerDebit($transaction);

        if (!$response) {
            throw new UseCaseException();
        }

        $this->eventManager->dispatch($transaction->getEvents());

        return $transaction;
    }
}