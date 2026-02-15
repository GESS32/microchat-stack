<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus\Idempotency;

use App\Domain\Bus\Idempotency\IdempotencyStatusEnum;
use App\Domain\Bus\Time\SystemTimeInterface;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'idempotency')]
#[ORM\UniqueConstraint(name: 'uniq_idem_key', columns: ['ikey'])]
class IdempotencyMapping
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 128)]
    private string $key;

    #[ORM\Column(type: 'integer')]
    public int $status = 1;

    #[ORM\Column(type: 'integer')]
    public int $attempts = 0;

    #[ORM\Column(name: 'last_error', type: 'string', length: 1000, nullable: true)]
    public ?string $lastError = null;

    #[ORM\Column(name: 'created_at', type: 'datetime_immutable')]
    public DateTimeImmutable $createdAt;

    #[ORM\Column(name: 'updated_at', type: 'datetime_immutable')]
    public DateTimeImmutable $updatedAt;

    public function __construct(string $key, SystemTimeInterface $time)
    {
        $this->key = $key;
        $this->createdAt = $time->now();
        $this->updatedAt = $time->now();
    }

    public function mark(IdempotencyStatusEnum $status, SystemTimeInterface $time, ?string $message = null): void
    {
        $this->status = $status->value;
        $this->updatedAt = $time->now();

        if ($status === IdempotencyStatusEnum::FAILED) {
            $this->attempts++;
            $this->lastError = $message ?: $this->lastError;
        }
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
