<?php

declare(strict_types=1);

namespace App\Modules\Payment\Services\Gateways;

use App\Modules\Payment\Services\Contracts\PaymentGateway;

class XenditGateway implements PaymentGateway
{
    public function __construct(
        private readonly string $secretKey,
        private readonly bool $isProduction,
    ) {}

    public function createTransaction(array $params): array
    {
        return [
            'gateway' => 'xendit',
            'transaction_id' => 'XNT-' . strtoupper(bin2hex(random_bytes(8))),
            'payment_url' => $this->isProduction
                ? 'https://checkout.xendit.co/id/invoice'
                : 'https://checkout-staging.xendit.co/id/invoice',
            'invoice_url' => 'https://xendit.id/inv/' . bin2hex(random_bytes(8)),
            'status' => 'pending',
        ];
    }

    public function checkStatus(string $transactionId): array
    {
        return [
            'gateway' => 'xendit',
            'transaction_id' => $transactionId,
            'status' => 'pending',
        ];
    }

    public function refund(string $transactionId, float $amount, string $reason): array
    {
        return [
            'gateway' => 'xendit',
            'transaction_id' => $transactionId,
            'refund_id' => 'RFN-' . strtoupper(bin2hex(random_bytes(8))),
            'status' => 'processed',
        ];
    }
}
