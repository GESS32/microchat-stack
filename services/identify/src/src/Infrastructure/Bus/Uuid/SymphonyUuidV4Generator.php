<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus\Uuid;

use App\Domain\Bus\Uuid\UuidGeneratorInterface;
use Symfony\Component\Uid\UuidV4;

class SymphonyUuidV4Generator implements UuidGeneratorInterface
{
    public function handle(): string
    {
        $uuid = new UuidV4();
        return $uuid->toRfc4122();
    }
}
