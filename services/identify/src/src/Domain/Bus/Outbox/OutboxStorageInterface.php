<?php

declare(strict_types=1);

namespace App\Domain\Bus\Outbox;

interface OutboxStorageInterface
{
    public function add(OutboxMessageCommand $message): void;

    /**
     * Reserve a batch of messages for processing.
     *
     * @return OutboxMessageCommand[]
     */
    public function reserveBatch(string $channel, int $limit, OutboxStatus $markAs = OutboxStatus::PROCESSING): array;

    public function mark(string $id, OutboxStatus $status): void;
}
