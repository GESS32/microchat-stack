<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus\Middleware;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

final readonly class DoctrineTransactionMiddleware implements MiddlewareInterface
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $this->entityManager->beginTransaction();
        try {
            $result = $stack->next()->handle($envelope, $stack);

            $this->entityManager->flush();
            $this->entityManager->commit();

            return $result;
        } catch (ExceptionInterface $exception) {
            $this->entityManager->rollback();
            throw $exception;
        }
    }
}
