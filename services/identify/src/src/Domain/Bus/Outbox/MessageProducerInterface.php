<?php

declare(strict_types=1);

namespace App\Domain\Bus\Outbox;

interface MessageProducerInterface
{
    public function publish(OutboxMessageCommand $message): void;
}
