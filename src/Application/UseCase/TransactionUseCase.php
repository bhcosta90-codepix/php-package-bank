<?php

declare(strict_types=1);

namespace CodePix\Bank\Application\UseCase;

use BRCas\CA\Contracts\Event\EventManagerInterface;
use BRCas\CA\Contracts\Transaction\DatabaseTransactionInterface;
use CodePix\Bank\Application\Exception\NotFoundException;
use CodePix\Bank\Application\Exception\UseCaseException;
use CodePix\Bank\Domain\Entities\Enum\PixKey\KindPixKey;
use CodePix\Bank\Domain\Entities\Transaction;
use CodePix\Bank\Domain\Repository\PixKeyRepositoryInterface;
use CodePix\Bank\Domain\Repository\TransactionRepositoryInterface;
use Costa\Entity\Exceptions\NotificationException;
use Costa\Entity\ValueObject\Uuid;
use Throwable;

class TransactionUseCase
{
    public function __construct(
        protected PixKeyRepositoryInterface $pixKeyRepository,
        protected TransactionRepositoryInterface $transactionRepository,
        protected EventManagerInterface $eventManager,
        protected DatabaseTransactionInterface $databaseTransaction,
    ) {
        //
    }

    /**
     * @throws NotFoundException
     * @throws NotificationException
     * @throws UseCaseException
     * @throws Throwable
     */
    public function registerDebit(
        string $account,
        float $value,
        string $kind,
        string $key,
        string $description
    ): Transaction {
        if (!$account = $this->pixKeyRepository->findAccount($account, false)) {
            throw new NotFoundException('Account not found');
        }

        if (!$pix = $this->pixKeyRepository->findKeyByKind($kind, $key)) {
            throw new NotFoundException('Pix not found');
        }

        try {
            $transaction = new Transaction(
                accountFrom: $account,
                value: $value,
                kind: $pix->kind,
                key: $pix->key,
                description: $description,
            );

            $response = $this->transactionRepository->registerDebit($transaction);

            if (!$response) {
                throw new UseCaseException('Register transaction with error');
            }

            $this->eventManager->dispatch($transaction->getEvents());
            $this->databaseTransaction->commit();
            return $transaction;
        } catch (Throwable $e) {
            $this->databaseTransaction->rollback();
            throw $e;
        }
    }

    /**
     * @throws NotFoundException
     * @throws NotificationException
     * @throws UseCaseException
     * @throws Throwable
     */
    public function registerCredit(
        string $debit,
        string $account,
        float $value,
        string $kind,
        string $key,
        string $description
    ): Transaction {
        if (!$account = $this->pixKeyRepository->findAccount($account, true)) {
            throw new NotFoundException('Account not found');
        }

        $transaction = new Transaction(
            accountFrom: $account,
            value: $value,
            kind: KindPixKey::from($kind),
            key: $key,
            description: $description,
            debit: new Uuid($debit),
        );

        $transaction->completed();

        try {
            $response = $this->transactionRepository->registerCredit($transaction);

            if (!$response) {
                throw new UseCaseException('Register transaction with error');
            }

            if (!$this->pixKeyRepository->updateAccount($account)) {
                throw new UseCaseException('Unable to save account');
            }

            $this->databaseTransaction->commit();
            $this->eventManager->dispatch($transaction->getEvents());

            return $transaction;
        } catch (Throwable $e) {
            $this->databaseTransaction->rollback();
            throw $e;
        }
    }

    /**
     * @throws Throwable
     * @throws UseCaseException
     */
    public function confirmTransaction(string $id): Transaction
    {
        try {
            if ($transaction = $this->transactionRepository->find($id)) {
                $transaction->completed();
                $this->transactionRepository->save($transaction);
                $this->pixKeyRepository->updateAccount($transaction->accountFrom);
                $this->databaseTransaction->commit();
                $this->eventManager->dispatch($transaction->getEvents());
                return $transaction;
            }

            throw new UseCaseException();
        } catch (Throwable $e) {
            $this->databaseTransaction->rollback();
            throw $e;
        }
    }
}