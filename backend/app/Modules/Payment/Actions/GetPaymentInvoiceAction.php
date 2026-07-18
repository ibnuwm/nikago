<?php

declare(strict_types=1);

namespace App\Modules\Payment\Actions;

use App\Modules\Payment\Models\Payment;
use App\Modules\Payment\Resources\InvoiceResource;
use Illuminate\Contracts\Auth\Authenticatable;

class GetPaymentInvoiceAction
{
    public function execute(Authenticatable $user, string $uuid): InvoiceResource
    {
        $payment = Payment::query()
            ->forUser($user->id)
            ->where('uuid', $uuid)
            ->with(['items', 'method'])
            ->firstOrFail();

        return new InvoiceResource($payment);
    }
}
