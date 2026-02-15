<?php

declare(strict_types=1);

namespace App\Domain\User\Exception;

class UniqueException extends ValidationException
{
    public function __construct(string $fieldName, ?ValidationException $previous = null)
    {
        parent::__construct(
            codeName: "{$fieldName}_unique",
            message: "The $fieldName already been taken.",
            previous: $previous
        );
    }
}
