<?php

declare(strict_types=1);

namespace App\Core\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class DomainException extends Exception
{
    private string $errorCode;

    private int $statusCode;

    private mixed $context;

    public function __construct(
        string $message,
        string $errorCode = 'DOMAIN_ERROR',
        int $statusCode = 422,
        mixed $context = null,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, 0, $previous);
        $this->errorCode = $errorCode;
        $this->statusCode = $statusCode;
        $this->context = $context;
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'error' => [
                'code' => $this->errorCode,
                'message' => $this->getMessage(),
                'context' => $this->context,
            ],
        ], $this->statusCode);
    }

    public function getErrorCode(): string
    {
        return $this->errorCode;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
