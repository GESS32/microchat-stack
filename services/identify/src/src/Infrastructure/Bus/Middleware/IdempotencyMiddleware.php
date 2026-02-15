<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus\Middleware;

use App\Domain\Bus\Command\CommandInterface;
use App\Domain\Bus\Idempotency\IdempotencyStatusEnum;
use App\Domain\Bus\Idempotency\IdempotencyStorageInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

final readonly class IdempotencyMiddleware implements MiddlewareInterface
{
    public function __construct(private IdempotencyStorageInterface $storage) {}

    /**
     * @throws ExceptionInterface
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();

        if (!$message instanceof CommandInterface || $message->getIdempotencyKey() === null) {
            return $stack->next()->handle($envelope, $stack);
        }

        $record = $this->storage->resolve($message->getIdempotencyKey());

        if ($record->status === IdempotencyStatusEnum::HANDLED) {
            return $envelope;
        }

        try {
            $result = $stack->next()->handle($envelope, $stack);
            $record->status = IdempotencyStatusEnum::HANDLED;

            $this->storage->update($record);

            return $result;
        } catch (ExceptionInterface $exception) {
            $record->status = IdempotencyStatusEnum::FAILED;
            $record->lastError = $exception->getMessage();

            $this->storage->update($record);

            throw $exception;
        }
    }
}
