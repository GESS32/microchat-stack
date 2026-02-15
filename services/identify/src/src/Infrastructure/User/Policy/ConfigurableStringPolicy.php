<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Policy;

use App\Domain\User\Policy\StringPolicyInterface;

abstract class ConfigurableStringPolicy implements StringPolicyInterface
{
    protected int $min;
    protected int $max;
    protected ?string $regex;

    public function min(): int
    {
        return $this->min;
    }

    public function max(): int
    {
        return $this->max;
    }

    public function regex(): string
    {
        return $this->regex;
    }
}
