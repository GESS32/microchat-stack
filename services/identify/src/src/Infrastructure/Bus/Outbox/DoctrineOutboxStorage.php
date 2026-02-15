<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus\Outbox;

use App\Domain\Bus\Outbox\OutboxMessageCommand;
use App\Domain\Bus\Outbox\OutboxStatus;
use App\Domain\Bus\Outbox\OutboxStorageInterface;
use App\Domain\Bus\Time\SystemTimeInterface;
use Doctrine\ORM\EntityManagerInterface;

final readonly class DoctrineOutboxStorage implements OutboxStorageInterface
{
    public function __construct(private EntityManagerInterface $entityManager, private SystemTimeInterface $time) {}

    public function add(OutboxMessageCommand $message): void
    {
        $now = $this->time->now();

        $entity = new OutboxMessageMapping(
            id: $message->id,
            channel: $message->channel,
            eventName: $message->eventName,
            aggregateId: $message->aggregateId,
            payload: $message->payload,
            headers: $message->headers,
            occurredAt: $message->occurredAt,
            availableAt: $message->availableAt,
            updatedAt: $now,
            createdAt: $now,
            partitionKey: $message->partitionKey,
        );

        $entity->mark(OutboxStatus::PENDING, $this->time->now());
        $this->entityManager->persist($entity);
    }

    public function reserveBatch(string $channel, int $limit, OutboxStatus $markAs = OutboxStatus::PROCESSING): array
    {
        $now = $this->time->now();



        $qb = $this->entityManager->createQueryBuilder();
        $qb->select('o')
            ->from(OutboxMessageMapping::class, 'o')
            ->where('o.channel = :channel')
            ->andWhere('o.status = :status')
            ->andWhere('o.availableAt <= :now')
            ->setParameter('channel', $channel)
            ->setParameter('status', OutboxStatus::PENDING->value)
            ->setParameter('now', $now)
            ->orderBy('o.availableAt', 'ASC')
            ->setMaxResults($limit);

        $messages = $qb->getQuery()->getResult();

        foreach ($messages as $message) {
            /** @var OutboxMessageMapping $message */
            $message->mark($markAs, $this->time->now());
        }

        $this->entityManager->flush();

        return array_map(fn(OutboxMessageMapping $m) => new OutboxMessageCommand(
            id: $m->id,
            channel: $m->channel,
            eventName: $m->eventName,
            aggregateId: $m->aggregateId,
            payload: $m->payload,
            headers: $m->headers,
            occurredAt: $m->occurredAt,
            availableAt: $m->availableAt,
            partitionKey: $m->partitionKey,
        ), $messages);
    }

    public function mark(string $id, OutboxStatus $status): void
    {
        // TODO: Implement mark() method.
    }
}
