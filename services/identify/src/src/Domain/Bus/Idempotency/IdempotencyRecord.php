<?php

declare(strict_types=1);

namespace App\Domain\Bus\Idempotency;

use DateTimeImmutable;

class IdempotencyRecord
{
    public function __construct(
        public IdempotencyStatusEnum $status,
        public readonly DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt,
        public readonly string $key,
        public readonly int $attempts = 0,
        public ?string $lastError = null,
    ) {}
}
