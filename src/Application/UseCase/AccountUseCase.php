<?php

declare(strict_types=1);

namespace CodePix\Bank\Application\UseCase;

use BRCas\CA\ValueObject\Password;
use CodePix\Bank\Application\Exception\NotFoundException;
use CodePix\Bank\Domain\Entities\Account;
use CodePix\Bank\Domain\Repository\PixKeyRepositoryInterface;
use Costa\Entity\ValueObject\Uuid;

class AccountUseCase
{
    public function __construct(
        protected PixKeyRepositoryInterface $pixKeyRepository,
    ) {
        //
    }

    /**
     * @throws NotFoundException
     */
    public function register(string $bank, string $name, string $agency, string $password): Account
    {
        do {
            $number = (string)rand(000000, 9999999);
        } while ($this->pixKeyRepository->verifyNumber($agency, $number));

        $agency = $this->pixKeyRepository->getAgencyByCode($agency);

        if (empty($agency)) {
            throw new NotFoundException('Agency not found');
        }

        $account = new Account(
            name: $name,
            bank: new Uuid($bank),
            agency: new Uuid($agency),
            number: $number,
            password: new Password($password)
        );

        $this->pixKeyRepository->addAccount($account);

        return $account;
    }

    /**
     * @throws NotFoundException
     */
    public function find(string $id): Account
    {
        if (!$account = $this->pixKeyRepository->findAccount($id)) {
            throw new NotFoundException('Pix not found');
        }

        return $account;
    }
}