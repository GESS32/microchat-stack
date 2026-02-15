<?php

declare(strict_types=1);

namespace App\Domain\Bus\Idempotency;

use DomainException;
use Throwable;

class IdempotencyRecordNotFoundException extends DomainException
{
    public function __construct(
        string $message = 'Idempotency record not found.',
        int $code = 0,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
