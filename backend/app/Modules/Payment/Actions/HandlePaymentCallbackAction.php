<?php

declare(strict_types=1);

namespace App\Modules\Payment\Actions;

use App\Modules\Payment\Models\Payment;
use App\Modules\Payment\Models\PaymentCallback;
use App\Modules\Payment\Models\PaymentTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HandlePaymentCallbackAction
{
    public function execute(Request $request, string $gateway): JsonResponse
    {
        $callback = PaymentCallback::query()->create([
            'gateway' => $gateway,
            'headers' => $request->headers->all(),
            'body' => $request->all(),
            'signature' => $request->header('X-Signature') ?? $request->input('signature'),
            'status' => 'received',
        ]);

        try {
            $payment = $this->resolvePayment($gateway, $request);

            if ($payment) {
                $callback->payment_id = $payment->id;
                $callback->status = 'processed';
                $callback->processed_at = now();
                $callback->save();

                $this->updatePaymentStatus($payment, $request);
            } else {
                $callback->status = 'unresolved';
                $callback->save();
            }

            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            Log::error('Payment callback error', [
                'gateway' => $gateway,
                'error' => $e->getMessage(),
                'callback_id' => $callback->id,
            ]);

            $callback->status = 'failed';
            $callback->save();

            return response()->json(['success' => false, 'message' => 'Callback processing failed'], 500);
        }
    }

    private function resolvePayment(string $gateway, Request $request): ?Payment
    {
        $orderId = match ($gateway) {
            'midtrans' => $request->input('order_id'),
            'xendit' => $request->input('external_id'),
            default => null,
        };

        if ($orderId === null) {
            return null;
        }

        return Payment::query()->where('invoice_number', $orderId)->first();
    }

    private function updatePaymentStatus(Payment $payment, Request $request): void
    {
        $transactionStatus = $request->input('transaction_status')
            ?? $request->input('status')
            ?? 'unknown';

        $fraudStatus = $request->input('fraud_status', 'accept');

        $newStatus = match (true) {
            $transactionStatus === 'capture' && $fraudStatus === 'accept' => 'paid',
            $transactionStatus === 'settlement' => 'paid',
            $transactionStatus === 'pending' => 'pending',
            $transactionStatus === 'deny', $transactionStatus === 'cancel' => 'failed',
            $transactionStatus === 'expire' => 'expired',
            $transactionStatus === 'refund', $transactionStatus === 'partial_refund' => 'refunded',
            default => $payment->status,
        };

        PaymentTransaction::query()->create([
            'payment_id' => $payment->id,
            'gateway' => $payment->method?->provider ?? 'unknown',
            'transaction_id' => $request->input('transaction_id'),
            'type' => 'callback',
            'request' => [],
            'response' => $request->all(),
            'status' => $newStatus,
        ]);

        $payment->status = $newStatus;
        if ($newStatus === 'paid' && $payment->paid_at === null) {
            $payment->paid_at = now();
        }
        $payment->save();
    }
}
