<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

interface EventInterface
{
    public function getPayload(): array;
}
