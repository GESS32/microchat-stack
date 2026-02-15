<?php

declare(strict_types=1);

namespace App\Domain\User\Services;

use App\Domain\User\Aggregate\User;
use App\Domain\User\Event\EventRecorderInterface;
use App\Domain\User\Event\UserRegistered;
use App\Domain\User\Exception\UniqueException;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Domain\User\System\ClockInterface;
use App\Domain\User\System\SecretHasherInterface;
use App\Domain\User\ValueObject\Email;
use App\Domain\User\ValueObject\FirstName;
use App\Domain\User\ValueObject\Id;
use App\Domain\User\ValueObject\LastName;
use App\Domain\User\ValueObject\Nickname;

readonly class Registrar
{
    public function __construct(
        private UserRepositoryInterface $repository,
        private ClockInterface $clock,
        private SecretHasherInterface $hasher,
        private EventRecorderInterface $eventRecorder
    ) {}

    public function register(
        Id $id,
        Email $email,
        Nickname $nickname,
        FirstName $firstName,
        LastName $lastName,
        string $plainPassword
    ): User {
        if ($this->repository->findByEmail((string) $email)) {
            throw new UniqueException('email');
        }

        if ($this->repository->findByNickname((string) $nickname)) {
            throw new UniqueException('nickname');
        }

        $hashedPassword = $this->hasher->handle($plainPassword);
        $now = $this->clock->now();

        $user = new User(
            id: $id,
            email: $email,
            nickname: $nickname,
            firstName: $firstName,
            lastName: $lastName,
            createdAt: $now,
            updatedAt: $now,
            hashedPassword: $hashedPassword
        );

        $this->repository->save($user);

        $this->eventRecorder->collect(new UserRegistered(
            userId: (string) $id,
            email: (string) $email,
            nickname: (string) $nickname,
            firstName: (string) $firstName,
            lastName: (string) $lastName,
        ));

        return $user;
    }
}
