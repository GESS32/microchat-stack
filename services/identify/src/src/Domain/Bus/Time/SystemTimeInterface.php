<?php

declare(strict_types=1);

namespace App\Domain\Bus\Time;

use DateTimeImmutable;

interface SystemTimeInterface
{
    public function now(): DateTimeImmutable;
}
