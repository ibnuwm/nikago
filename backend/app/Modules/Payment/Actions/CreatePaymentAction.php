<?php

declare(strict_types=1);

namespace App\Modules\Payment\Actions;

use App\Modules\Payment\Models\Payment;
use App\Modules\Payment\Models\PaymentItem;
use App\Modules\Payment\Resources\PaymentResource;
use App\Modules\Payment\Services\InvoiceService;
use Illuminate\Contracts\Auth\Authenticatable;

class CreatePaymentAction
{
    public function __construct(
        private readonly InvoiceService $invoiceService,
    ) {}

    public function execute(Authenticatable $user, array $data): PaymentResource
    {
        $totalAmount = collect($data['items'])->sum(fn (array $item): float => $item['amount'] * ($item['quantity'] ?? 1));

        $payment = Payment::query()->create([
            'tenant_id' => $user->tenant_id ?? 1,
            'user_id' => $user->id,
            'invoice_number' => $this->invoiceService->generateInvoiceNumber(),
            'amount' => $totalAmount,
            'expired_at' => now()->addHours(24),
        ]);

        foreach ($data['items'] as $item) {
            PaymentItem::query()->create([
                'payment_id' => $payment->id,
                'item_type' => $item['item_type'],
                'item_id' => $item['item_id'] ?? null,
                'name' => $item['name'],
                'amount' => $item['amount'],
                'quantity' => $item['quantity'] ?? 1,
            ]);
        }

        return new PaymentResource(
            $payment->load(['items'])
        );
    }
}
