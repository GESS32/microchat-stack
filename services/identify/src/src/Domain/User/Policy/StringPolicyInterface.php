<?php

declare(strict_types=1);

namespace App\Domain\User\Policy;

interface StringPolicyInterface
{
    public function min(): int;

    public function max(): int;

    public function regex(): ?string;
}
