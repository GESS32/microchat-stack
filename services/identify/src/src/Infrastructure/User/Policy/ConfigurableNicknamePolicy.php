<?php

declare(strict_types=1);

namespace App\Infrastructure\User\Policy;

class ConfigurableNicknamePolicy extends ConfigurableStringPolicy
{
    public function __construct(
        protected int $min = 3,
        protected int $max = 24,
        protected ?string $regex = '/^[A-Za-z0-9_]+$/'
    ) {}
}
