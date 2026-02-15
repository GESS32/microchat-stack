<?php

declare(strict_types=1);

namespace App\Domain\Bus\Command;

interface CommandInterface
{
    public function getIdempotencyKey(): ?string;
}
