<?php

declare(strict_types=1);

namespace App\Modules\Payment\Actions;

use App\Modules\Payment\Models\Payment;
use App\Modules\Payment\Models\PaymentMethod;
use App\Modules\Payment\Models\PaymentTransaction;
use App\Modules\Payment\Resources\PaymentResource;
use App\Modules\Payment\Services\GatewayFactory;
use Illuminate\Contracts\Auth\Authenticatable;

class ProcessPaymentAction
{
    public function execute(Authenticatable $user, string $uuid, array $data): array
    {
        $payment = Payment::query()
            ->with('items')
            ->forUser($user->id)
            ->where('uuid', $uuid)
            ->where('status', 'pending')
            ->firstOrFail();

        $method = PaymentMethod::query()
            ->where('code', $data['payment_method_code'])
            ->where('is_active', true)
            ->firstOrFail();

        $gateway = GatewayFactory::make($data['gateway']);

        $gatewayResponse = $gateway->createTransaction([
            'invoice_number' => $payment->invoice_number,
            'amount' => $payment->amount,
            'items' => $payment->items->toArray(),
        ]);

        PaymentTransaction::query()->create([
            'payment_id' => $payment->id,
            'gateway' => $data['gateway'],
            'transaction_id' => $gatewayResponse['transaction_id'],
            'type' => 'charge',
            'request' => $data,
            'response' => $gatewayResponse,
            'status' => 'pending',
        ]);

        $payment->payment_method_id = $method->id;
        $payment->save();

        return [
            'payment' => new PaymentResource($payment->load(['items', 'method'])),
            'gateway' => $gatewayResponse,
        ];
    }
}
