<?php

declare(strict_types=1);

namespace App\Application\Register;

use App\Domain\Bus\Command\CommandInterface;

readonly class RegisterUserCommand implements CommandInterface
{
    public function __construct(
        public string $email,
        public string $nickname,
        public string $plainPassword,
        public string $firstName,
        public ?string $lastName = null,
        public ?string $idempotencyKey = null,
    ) {}

    public function getIdempotencyKey(): ?string
    {
        return $this->idempotencyKey;
    }
}
