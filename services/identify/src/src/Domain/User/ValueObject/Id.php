<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

use InvalidArgumentException;
use Stringable;

final readonly class Id implements Stringable
{
    private string $value;

    public function __construct(string $value)
    {
        if (preg_match('/^[0-9a-fA-F-]{36}$/', $value) === false) {
            throw new InvalidArgumentException('Invalid identifier value');
        }

        $this->value = strtolower($value);
    }


    public function __toString(): string
    {
        return $this->value;
    }
}
