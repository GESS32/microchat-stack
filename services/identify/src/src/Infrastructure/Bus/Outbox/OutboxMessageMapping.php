<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus\Outbox;

use App\Domain\Bus\Outbox\OutboxStatus;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'outbox_messages')]
#[ORM\Index(name: 'idx_outbox_ready', columns: ['status', 'available_at'])]
#[ORM\Index(name: 'idx_outbox_channel', columns: ['channel', 'status', 'available_at'])]
class OutboxMessageMapping
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 26)]
    private string $id;

    #[ORM\Column(type: 'string', length: 64)]
    public string $channel;

    #[ORM\Column(name: 'event_name', type: 'string', length: 255)]
    public string $eventName;

    #[ORM\Column(name: 'aggregate_id', type: 'string', length: 64)]
    public string $aggregateId;

    #[ORM\Column(type: 'json')]
    public array $payload = [];

    #[ORM\Column(type: 'json')]
    public array $headers = [];

    #[ORM\Column(name: 'occurred_at', type: 'datetime_immutable')]
    public DateTimeImmutable $occurredAt;

    #[ORM\Column(type: 'datetime_immutable')]
    public DateTimeImmutable $availableAt;

    #[ORM\Column(name: 'updated_at', type: 'datetime_immutable')]
    public DateTimeImmutable $updatedAt;

    #[ORM\Column(name: 'created_at', type: 'datetime_immutable')]
    public DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'smallint')]
    public int $status = OutboxStatus::PENDING->value;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    public int $attempts = 0;

    #[ORM\Column(name: 'last_error', type: 'text', nullable: true)]
    public ?string $lastError = null;

    #[ORM\Column(name: 'partition_key', type: 'string', length: 128, nullable: true)]
    public ?string $partitionKey = null;

    #[ORM\Column(name: 'published_at', type: 'datetime_immutable', nullable: true)]
    public ?DateTimeImmutable $publishedAt = null;

    public function __construct(
        string $id,
        string $channel,
        string $eventName,
        string $aggregateId,
        array $payload,
        array $headers,
        DateTimeImmutable $occurredAt,
        DateTimeImmutable $availableAt,
        DateTimeImmutable $updatedAt,
        DateTimeImmutable $createdAt,
        ?string $partitionKey = null
    ) {
        $this->id = $id;
        $this->channel = $channel;
        $this->eventName = $eventName;
        $this->aggregateId = $aggregateId;
        $this->payload = $payload;
        $this->headers = $headers;
        $this->occurredAt = $occurredAt;
        $this->availableAt = $availableAt;
        $this->updatedAt = $updatedAt;
        $this->createdAt = $createdAt;
        $this->partitionKey = $partitionKey;
    }

    public function mark(OutboxStatus $status, DateTimeImmutable $time): void
    {
        $this->status = $status->value;
        $this->attempts++;

        if ($status === OutboxStatus::PUBLISHED) {
            $this->publishedAt = $time;
        }
    }

    public function getId(): string
    {
        return $this->id;
    }
}
