<?php

declare(strict_types=1);

namespace App\Interface\Controller;

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
}
