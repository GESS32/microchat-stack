<?php

declare(strict_types=1);

namespace App\Interface\Controller;

use App\Application\Register\RegisterUserCommand;
use Symfony\Component\Validator\Constraints as Assert;

final class RegisterUserRequest
{
    #[Assert\NotBlank(groups: ['create'])]
    #[Assert\Email(groups: ['create'])]
    public string $email;

    #[Assert\NotBlank(groups: ['create'])]
    #[Assert\Length(min: 3, max: 24, groups: ['create'])]
    public string $nickname;

    #[Assert\NotBlank(groups: ['create'])]
    #[Assert\Length(min: 8, groups: ['create'])]
    public string $plainPassword;

    #[Assert\NotBlank(groups: ['create'])]
    #[Assert\Length(min: 1, max: 50, groups: ['create'])]
    public string $firstName;

    #[Assert\Length(max: 50, groups: ['create'])]
    public ?string $lastName = null;

    public function toCommand(?string $idempotencyKey = null): RegisterUserCommand
    {
        return new RegisterUserCommand(
            email: $this->normalizeRequiredString($this->email),
            nickname: $this->normalizeRequiredString($this->nickname),
            plainPassword: $this->plainPassword,
            firstName: $this->normalizeRequiredString($this->firstName),
            lastName: $this->normalizeOptionalString($this->lastName),
            idempotencyKey: $idempotencyKey,
        );
    }

    private function normalizeRequiredString(string $value): string
    {
        return trim($value);
    }

    private function normalizeOptionalString(?string $value): ?string
    {
        $normalized = null;

        if (is_null($value) === false) {
            $normalized = trim($value);
            $normalized = $normalized === '' ? null : $normalized;
        }

        return $normalized;
    }
}
