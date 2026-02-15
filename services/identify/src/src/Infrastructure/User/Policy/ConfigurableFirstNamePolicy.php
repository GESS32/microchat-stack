<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Policy;

class ConfigurableFirstNamePolicy extends ConfigurableStringPolicy
{
    public function __construct(
        protected int $min = 1,
        protected int $max = 50,
        protected ?string $regex = null
    ) {}
}
