<?php

declare(strict_types=1);

namespace App\Modules\Payment\Resources;

use App\Core\Base\Resource;
use Carbon\Carbon;

class PaymentResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->uuid,
            'invoice_number' => $this->resource->invoice_number,
            'amount' => (float) $this->resource->amount,
            'status' => $this->resource->status,
            'payment_method' => $this->whenLoaded('method', fn () => [
                'code' => $this->resource->method->code,
                'name' => $this->resource->method->name,
                'provider' => $this->resource->method->provider,
            ]),
            'paid_at' => $this->resource->paid_at instanceof Carbon
                ? $this->resource->paid_at->toIsoString()
                : null,
            'expired_at' => $this->resource->expired_at instanceof Carbon
                ? $this->resource->expired_at->toIsoString()
                : null,
            'notes' => $this->resource->notes,
            'items' => PaymentItemResource::collection($this->whenLoaded('items')),
            'refunds' => RefundResource::collection($this->whenLoaded('refunds')),
            'created_at' => $this->resource->created_at instanceof Carbon
                ? $this->resource->created_at->toIsoString()
                : null,
        ];
    }
}
