<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

final readonly class UserRegistered implements EventInterface
{
    public function __construct(
        public string $userId,
        public string $email,
        public string $nickname,
        public string $firstName,
        public ?string $lastName,
    ) {}

    public function getPayload(): array
    {
        return [
            'user_id' => $this->userId,
            'email' => $this->email,
            'nickname' => $this->nickname,
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
        ];
    }
}
