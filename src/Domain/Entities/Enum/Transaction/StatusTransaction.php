<?php

declare(strict_types=1);

namespace CodePix\Bank\Domain\Entities\Enum\Transaction;

enum StatusTransaction: string
{
    case PENDING = 'pending';

    case COMPLETED = 'completed';

    case CONFIRMED = 'confirmed';

    case ERROR = 'error';
}