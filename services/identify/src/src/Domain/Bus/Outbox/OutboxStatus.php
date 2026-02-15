<?php

declare(strict_types=1);

namespace App\Domain\Bus\Outbox;

enum OutboxStatus: int
{
    case PENDING = 0;
    case PROCESSING = 1;
    case PUBLISHED = 2;
    case FAILED = 3;
}
