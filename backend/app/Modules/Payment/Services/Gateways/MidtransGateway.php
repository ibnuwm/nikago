<?php

declare(strict_types=1);

namespace App\Modules\Payment\Services\Gateways;

use App\Modules\Payment\Services\Contracts\PaymentGateway;

class MidtransGateway implements PaymentGateway
{
    public function __construct(
        private readonly string $serverKey,
        private readonly bool $isProduction,
    ) {}

    public function createTransaction(array $params): array
    {
        return [
            'gateway' => 'midtrans',
            'transaction_id' => 'MID-' . strtoupper(bin2hex(random_bytes(8))),
            'payment_url' => $this->isProduction
                ? 'https://app.midtrans.com/snap/v1/transactions'
                : 'https://app.sandbox.midtrans.com/snap/v1/transactions',
            'token' => 'midtrans-token-' . bin2hex(random_bytes(16)),
            'status' => 'pending',
        ];
    }

    public function checkStatus(string $transactionId): array
    {
        return [
            'gateway' => 'midtrans',
            'transaction_id' => $transactionId,
            'status' => 'pending',
        ];
    }

    public function refund(string $transactionId, float $amount, string $reason): array
    {
        return [
            'gateway' => 'midtrans',
            'transaction_id' => $transactionId,
            'refund_id' => 'RFN-' . strtoupper(bin2hex(random_bytes(8))),
            'status' => 'processed',
        ];
    }
}
