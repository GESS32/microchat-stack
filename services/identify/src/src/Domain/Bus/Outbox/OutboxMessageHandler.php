<?php

declare(strict_types=1);

namespace App\Domain\Bus\Outbox;

final readonly class OutboxMessageHandler
{
    public function __construct(private OutboxStorageInterface $storage) {}

    public function __invoke(OutboxMessageCommand $command): void
    {
        $this->storage->add($command);
    }
}
