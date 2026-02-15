<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

use InvalidArgumentException;
use Stringable;

final readonly class Nickname implements Stringable
{
    public string $value;
    public string $canonical;

    public function __construct(string $value, string $canonical)
    {
        if (empty($value)) {
            throw new InvalidArgumentException('Value cannot be empty');
        }

        if (empty($canonical)) {
            throw new InvalidArgumentException('Canonical cannot be empty');
        }

        $this->value = $value;
        $this->canonical = $canonical;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
