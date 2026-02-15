<?php

declare(strict_types=1);

namespace App\Domain\User\Aggregate;

use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\FirstName;
use App\Domain\User\ValueObject\Id;
use App\Domain\User\ValueObject\LastName;
use App\Domain\User\ValueObject\Nickname;
use DateTimeImmutable;

readonly class User
{
    public function __construct(
        public Id $id,
        public Email $email,
        public Nickname $nickname,
        public FirstName $firstName,
        public LastName $lastName,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt,
        public string $hashedPassword,
    ) {}
}
