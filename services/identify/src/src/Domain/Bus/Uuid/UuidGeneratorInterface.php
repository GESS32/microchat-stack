<?php

declare(strict_types=1);

namespace App\Domain\Bus\Uuid;

interface UuidGeneratorInterface
{
    public function handle(): string;
}
