<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Event;

use App\Domain\User\Event\EventInterface;
use App\Domain\User\Event\EventRecorderInterface;
use Symfony\Contracts\Service\ResetInterface;

class SymphonyBufferEventRecorder implements EventRecorderInterface, ResetInterface
{
    /** @var array|array<EventInterface>|EventInterface[] */
    private array $buffer = [];

    public function collect(EventInterface $event): void
    {
        $this->buffer[] = $event;
    }

    public function drain(): array
    {
        $events = $this->buffer;
        $this->reset();

        return $events;
    }

    public function reset(): void
    {
        $this->buffer = [];
    }
}
