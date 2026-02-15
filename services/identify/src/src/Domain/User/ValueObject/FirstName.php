<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

use InvalidArgumentException;
use Stringable;

final readonly class FirstName implements Stringable
{
    private string $value;

    public function __construct(string $value)
    {
        if (empty($value)) {
            throw new InvalidArgumentException('First name cannot be empty');
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
