<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus\Idempotency;

use App\Domain\Bus\Exception\TransactionRequiredException as IdempotencyTransactionRequiredException;
use App\Domain\Bus\Idempotency\IdempotencyRecord;
use App\Domain\Bus\Idempotency\IdempotencyStatusEnum;
use App\Domain\Bus\Idempotency\IdempotencyStorageInterface;
use App\Domain\Bus\Idempotency\IdempotencyRecordNotFoundException;
use App\Domain\Bus\Time\SystemTimeInterface;
use DateTimeImmutable;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\TransactionRequiredException;

final readonly class DoctrineIdempotencyStorage implements IdempotencyStorageInterface
{
    public function __construct(private EntityManagerInterface $entityManager, private SystemTimeInterface $time) {}

    public function resolve(string $key): IdempotencyRecord
    {
        $entity = null;

        try {
            /** @var IdempotencyMapping|null $entity */
            $entity = $this->entityManager->find(IdempotencyMapping::class, $key, LockMode::PESSIMISTIC_WRITE);
        } catch (TransactionRequiredException $exception) {
            throw new IdempotencyTransactionRequiredException(previous: $exception);
        } catch (ORMException) {}

        if ($entity === null) {
            $entity = new IdempotencyMapping($key, $this->time);
            $this->entityManager->persist($entity);
        }

        return new IdempotencyRecord(
            status: IdempotencyStatusEnum::from($entity->status),
            createdAt: $entity->createdAt,
            updatedAt: $entity->updatedAt,
            key: $entity->getKey(),
            attempts: $entity->attempts,
            lastError: $entity->lastError,
        );
    }

    /**
     * @throws IdempotencyRecordNotFoundException|IdempotencyTransactionRequiredException
     */
    public function update(IdempotencyRecord $record): void
    {
        try {
            /** @var IdempotencyMapping|null $entity */
            $entity = $this->entityManager->find(IdempotencyMapping::class, $record->key, LockMode::PESSIMISTIC_WRITE);
        } catch (TransactionRequiredException $exception) {
            throw new IdempotencyTransactionRequiredException(previous: $exception);
        } catch (ORMException $exception) {
            throw new IdempotencyRecordNotFoundException(previous: $exception);
        }

        if ($entity === null) {
            throw new IdempotencyRecordNotFoundException("Idempotency record not found: $record->key");
        }

        $entity->mark($record->status, $this->time, $record->lastError);
    }

    public function purgeOlderThan(DateTimeImmutable $threshold): int
    {
        $builder = $this->entityManager->createQueryBuilder()
            ->delete(IdempotencyMapping::class, 'i')
            ->where('i.updatedAt < :threshold')
            ->setParameter('threshold', $threshold);

        return $builder->getQuery()->execute();
    }
}
