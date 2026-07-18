<?php

declare(strict_types=1);

namespace App\Modules\Payment\Actions;

use App\Modules\Payment\Models\Payment;
use App\Modules\Payment\Models\PaymentTransaction;
use App\Modules\Payment\Models\Refund;
use App\Modules\Payment\Resources\RefundResource;
use App\Modules\Payment\Services\GatewayFactory;
use Illuminate\Contracts\Auth\Authenticatable;

class RefundPaymentAction
{
    public function execute(Authenticatable $user, string $uuid, array $data): RefundResource
    {
        $payment = Payment::query()
            ->forUser($user->id)
            ->where('uuid', $uuid)
            ->where('status', 'paid')
            ->firstOrFail();

        $refund = Refund::query()->create([
            'payment_id' => $payment->id,
            'amount' => $data['amount'],
            'reason' => $data['reason'],
        ]);

        $transaction = $payment->transactions()
            ->where('type', 'charge')
            ->where('status', 'paid')
            ->first();

        if ($transaction && $transaction->transaction_id) {
            $gateway = GatewayFactory::make($transaction->gateway);
            $response = $gateway->refund($transaction->transaction_id, (float) $data['amount'], $data['reason']);

            $refund->gateway_transaction_id = $response['refund_id'] ?? null;
            $refund->status = 'processed';
            $refund->save();

            PaymentTransaction::query()->create([
                'payment_id' => $payment->id,
                'gateway' => $transaction->gateway,
                'transaction_id' => $response['refund_id'] ?? null,
                'type' => 'refund',
                'request' => $data,
                'response' => $response,
                'status' => 'processed',
            ]);
        }

        return new RefundResource($refund);
    }
}
