<?php

declare(strict_types=1);

namespace App\Domain\User\System;

interface SecretHasherInterface
{
    public function handle(string $password): string;
}
