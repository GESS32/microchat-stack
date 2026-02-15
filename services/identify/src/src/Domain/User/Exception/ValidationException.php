<?php

declare(strict_types=1);

namespace App\Domain\User\Exception;

use DomainException;

abstract class ValidationException extends DomainException
{
    public readonly string $codeName;

    public function __construct(string $codeName = 'user', string $message = '', ?ValidationException $previous = null)
    {
        $this->codeName = $codeName;
        parent::__construct(message: $message, previous: $previous);
    }
}
