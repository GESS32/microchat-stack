<?php

declare(strict_types=1);

namespace App\Domain\User\System;

use DateTimeImmutable;

interface ClockInterface
{
    public function now(): DateTimeImmutable;
}
