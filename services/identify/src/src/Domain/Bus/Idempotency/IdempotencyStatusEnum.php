<?php

declare(strict_types=1);

namespace App\Domain\Bus\Idempotency;

enum IdempotencyStatusEnum: int
{
    case PENDING = 1;
    case HANDLED = 2;
    case FAILED = 3;
}
