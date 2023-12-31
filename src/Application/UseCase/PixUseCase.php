<?php

declare(strict_types=1);

namespace CodePix\Bank\Application\UseCase;

use CodePix\Bank\Application\Exception\NotFoundException;
use CodePix\Bank\Application\Exception\UseCaseException;
use CodePix\Bank\Application\integration\PixKeyIntegrationInterface;
use CodePix\Bank\Domain\Entities\Enum\PixKey\KindPixKey;
use CodePix\Bank\Domain\Entities\PixKey;
use CodePix\Bank\Domain\Repository\PixKeyRepositoryInterface;
use Exception;

class PixUseCase
{
    public function __construct(
        protected PixKeyRepositoryInterface $pixKeyRepository,
        protected PixKeyIntegrationInterface $pixKeyIntegration
    ) {
        //
    }

    /**
     * @throws NotFoundException
     * @throws UseCaseException
     * @throws Exception
     */
    public function register(string $kind, ?string $key, string $account): PixKey
    {
        if (!$account = $this->pixKeyRepository->findAccount($account, false)) {
            throw new NotFoundException('Account not found');
        }

        $response = $this->pixKeyIntegration->register(
            (string)$account->bank,
            $account->id(),
            $kind,
            $key
        );

        if ($response->status > 201) {
            throw new Exception();
        }

        $pix = new PixKey(
            reference: $response->id,
            bank: $account->bank,
            kind: KindPixKey::from($kind),
            account: $account,
            key: $response->key,
        );

        $response = $this->pixKeyRepository->register($pix);

        if (!$response) {
            throw new UseCaseException();
        }

        return $pix;
    }

    /**
     * @throws NotFoundException
     */
    public function find(string $kind, string $key): PixKey
    {
        if (!$pix = $this->pixKeyRepository->findKeyByKind($kind, $key)) {
            throw new NotFoundException('Pix not found');
        }

        return $pix;
    }
}