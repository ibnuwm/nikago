<?php

declare(strict_types=1);

namespace App\Core\Exceptions;

class NotFoundException extends DomainException
{
    public function __construct(
        string $resource = 'Resource',
        string|int|null $identifier = null,
        ?\Throwable $previous = null
    ) {
        $message = $identifier
            ? "{$resource} with identifier '{$identifier}' not found."
            : "{$resource} not found.";

        parent::__construct(
            message: $message,
            errorCode: 'NOT_FOUND',
            statusCode: 404,
            previous: $previous
        );
    }
}
