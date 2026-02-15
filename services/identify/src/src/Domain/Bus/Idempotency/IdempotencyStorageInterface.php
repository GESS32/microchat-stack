<?php

declare(strict_types=1);

namespace App\Domain\Bus\Idempotency;

use App\Domain\Bus\Exception\TransactionRequiredException;
use DateTimeImmutable;

interface IdempotencyStorageInterface
{
    /**
     * @throws TransactionRequiredException
     */
    public function resolve(string $key): IdempotencyRecord;

    /**
     * @throws IdempotencyRecordNotFoundException|TransactionRequiredException
     */
    public function update(IdempotencyRecord $record): void;

    /**
     * @return int Number of records deleted
     */
    public function purgeOlderThan(DateTimeImmutable $threshold): int;
}
