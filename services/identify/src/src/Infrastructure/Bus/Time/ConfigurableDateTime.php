<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus\Time;

use App\Domain\Bus\Time\SystemTimeInterface;
use DateTimeImmutable;

class ConfigurableDateTime implements SystemTimeInterface
{
    public function now(): DateTimeImmutable
    {
        return new DateTimeImmutable();
    }
}
