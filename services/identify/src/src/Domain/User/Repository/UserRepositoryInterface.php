<?php

declare(strict_types=1);

namespace App\Domain\User\Repository;

use App\Domain\User\Aggregate\User;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    public function findByEmail(string $email): ?User;

    public function findByNickname(string $nickname): ?User;

    public function delete(User $user): void;
}
