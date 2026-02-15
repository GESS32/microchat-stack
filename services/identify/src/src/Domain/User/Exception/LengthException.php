<?php

declare(strict_types=1);

namespace App\Domain\User\Exception;

class LengthException extends ValidationException
{
    public function __construct(
        public readonly int $min,
        public readonly int $max,
        string $codeName = 'value_length',
        string $message = '',
        ?ValidationException $previous = null
    ) {
        $message = empty($message) ? "The value length must be between in $min and $max" : $message;

        parent::__construct(
            codeName: $codeName,
            message: $message,
            previous: $previous,
        );
    }
}
