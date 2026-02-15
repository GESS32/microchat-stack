<?php

declare(strict_types=1);

namespace App\Domain\User\Exception;

class CharactersException extends ValidationException
{
    public function __construct(
        string $codeName = 'value_characters',
        string $message = 'Given value contains unsupported characters.',
        ?ValidationException $previous = null
    ) {
        parent::__construct(
            codeName: $codeName,
            message: $message,
            previous: $previous,
        );
    }
}
