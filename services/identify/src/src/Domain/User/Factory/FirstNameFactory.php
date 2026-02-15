<?php

declare(strict_types=1);

namespace App\Domain\User\Factory;

use App\Domain\User\Policy\StringPolicyInterface;
use App\Domain\User\ValueObject\FirstName;

class FirstNameFactory extends StringPolicyFactory
{
    public function __construct(protected StringPolicyInterface $policy) {}

    /**
     * @return FirstName
     */
    protected function make(string $original): mixed
    {
        return new FirstName($original);
    }

    protected function attributeName(string $name): string
    {
        return 'first_name';
    }
}
