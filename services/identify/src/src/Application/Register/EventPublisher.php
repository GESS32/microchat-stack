<?php

declare(strict_types=1);

namespace App\Application\Register;

use App\Domain\Bus\Outbox\MessageProducerInterface;
use App\Domain\Bus\Uuid\UuidGeneratorInterface;
use App\Domain\User\System\ClockInterface;

final readonly class EventPublisher
{
    public function __construct(
        ClockInterface $clock,
        UuidGeneratorInterface $uuidGenerator,
        MessageProducerInterface $producer,
    ) {}
}
