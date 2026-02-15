<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus\Messanger;

use App\Domain\Bus\Command\CommandBusInterface;
use App\Domain\Bus\Command\CommandInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class SymfonyCommandBus implements CommandBusInterface
{
    public function __construct(private MessageBusInterface $commandBus) {}

    /**
     * @throws ExceptionInterface
     */
    public function dispatch(CommandInterface $command): void
    {
        $this->commandBus->dispatch($command);
    }
}
