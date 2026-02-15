<?php

declare(strict_types=1);

namespace App\Domain\User\Factory;

use App\Domain\User\ValueObject\LastName;

class LastNameFactory extends StringPolicyFactory
{
    /**
     * @return LastName
     */
    protected function make(string $original): mixed
    {
        return new LastName($original);
    }

    protected function attributeName(string $name): string
    {
        return 'last_name';
    }
}
