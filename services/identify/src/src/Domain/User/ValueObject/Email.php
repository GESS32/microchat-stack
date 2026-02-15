<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

use InvalidArgumentException;
use Stringable;

final readonly class Email implements Stringable
{
    private string $value;

    public function __construct(string $value)
    {
        if (empty($value) || filter_var($value, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidArgumentException('Invalid email format');
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
