<?php

declare(strict_types=1);

namespace App\Modules\Payment\Services\Contracts;

interface PaymentGateway
{
    public function createTransaction(array $params): array;
    public function checkStatus(string $transactionId): array;
    public function refund(string $transactionId, float $amount, string $reason): array;
}
