<?php

declare(strict_types=1);

namespace App\Modules\Payment\Resources;

use App\Core\Base\Resource;

class PaymentItemResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'item_type' => $this->resource->item_type,
            'item_id' => $this->resource->item_id,
            'name' => $this->resource->name,
            'amount' => (float) $this->resource->amount,
            'quantity' => $this->resource->quantity,
        ];
    }
}
