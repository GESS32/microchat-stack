<?php

declare(strict_types=1);

namespace App\Domain\User\ValueObject;

use Stringable;

final readonly class LastName implements Stringable
{
    private string $value;

    public function __construct(?string $value = null)
    {
        $this->value = empty($value) ? null : $value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
