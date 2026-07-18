<?php

declare(strict_types=1);

namespace App\Modules\Payment\Services;

use App\Modules\Payment\Services\Contracts\PaymentGateway;
use App\Modules\Payment\Services\Gateways\MidtransGateway;
use App\Modules\Payment\Services\Gateways\XenditGateway;
use RuntimeException;

class GatewayFactory
{
    public static function make(string $gateway): PaymentGateway
    {
        return match ($gateway) {
            'midtrans' => new MidtransGateway(
                serverKey: config('services.midtrans.server_key', ''),
                isProduction: config('services.midtrans.is_production', false),
            ),
            'xendit' => new XenditGateway(
                secretKey: config('services.xendit.secret_key', ''),
                isProduction: config('services.xendit.is_production', false),
            ),
            default => throw new RuntimeException("Unsupported payment gateway: {$gateway}"),
        };
    }
}
