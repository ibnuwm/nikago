<?php

declare(strict_types=1);

namespace App\Core\Exceptions;

use Illuminate\Http\JsonResponse;

class ValidationException extends DomainException
{
    protected array $errors;

    public function __construct(
        array $errors,
        ?\Throwable $previous = null
    ) {
        parent::__construct(
            message: 'Validation failed.',
            errorCode: 'VALIDATION_ERROR',
            statusCode: 422,
            previous: $previous
        );
        $this->errors = $errors;
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $this->getMessage(),
            'errors' => $this->errors,
        ], $this->getStatusCode());
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
