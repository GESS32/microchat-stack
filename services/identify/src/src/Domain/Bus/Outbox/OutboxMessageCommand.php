<?php

declare(strict_types=1);

namespace App\Domain\Bus\Outbox;

use App\Domain\Bus\Command\CommandInterface;
use DateTimeImmutable;

final readonly class OutboxMessageCommand implements CommandInterface
{
    public function __construct(
        public string $id,
        public string $channel,
        public string $eventName,
        public string $aggregateId,
        public array $payload,
        public array $headers,
        public DateTimeImmutable $occurredAt,
        public DateTimeImmutable $availableAt,
        public ?string $idempotencyKey = null,
        public ?string $partitionKey = null,
    ) {}

    public function getIdempotencyKey(): ?string
    {
        return $this->idempotencyKey;
    }
}
