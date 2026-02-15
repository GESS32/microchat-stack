<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

interface EventRecorderInterface
{
    public function collect(EventInterface $event): void;

    /** @return array|EventInterface[] */
    public function drain(): array;
}
