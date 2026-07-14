<?php

declare(strict_types=1);

namespace App\Core\Exceptions;

class ForbiddenException extends DomainException
{
    public function __construct(
        string $message = 'You are not authorized to perform this action.',
        ?\Throwable $previous = null
    ) {
        parent::__construct(
            message: $message,
            errorCode: 'FORBIDDEN',
            statusCode: 403,
            previous: $previous
        );
    }
}
