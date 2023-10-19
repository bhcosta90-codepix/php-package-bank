<?php

declare(strict_types=1);

namespace CodePix\Bank\Domain\Repository;

use CodePix\Bank\Domain\Entities\Account;
use CodePix\Bank\Domain\Entities\PixKey;

interface PixKeyRepositoryInterface
{
    public function register(PixKey $pixKey): bool;

    public function findKeyByKind(string $key, string $kind): ?PixKey;

    public function addAccount(Account $account);

    public function findAccount(string $id): ?Account;

    public function verifyNumber(string $agency, string $number): bool;

    public function getAgencyByCode(string $id): ?string;
}