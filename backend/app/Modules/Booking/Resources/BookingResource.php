<?php

declare(strict_types=1);

namespace App\Modules\Booking\Resources;

use App\Core\Base\Resource;
use App\Modules\Booking\Models\Booking;
use App\Modules\Vendor\Resources\VendorPackageResource;
use App\Modules\Vendor\Resources\VendorResource;
use Carbon\Carbon;

/**
 * @property-read Booking $resource
 */
class BookingResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->uuid,
            'vendor_uuid' => $this->resource->vendor->uuid ?? null,
            'vendor_name' => $this->resource->vendor->business_name ?? null,
            'package_name' => $this->resource->package->name ?? null,
            'package_price' => $this->resource->package->price ?? null,
            'booking_date' => $this->resource->booking_date instanceof Carbon
                ? $this->resource->booking_date->toDateString()
                : $this->resource->booking_date,
            'event_date' => $this->resource->event_date instanceof Carbon
                ? $this->resource->event_date->toDateString()
                : $this->resource->event_date,
            'subtotal' => (float) $this->resource->subtotal,
            'discount' => (float) $this->resource->discount,
            'total' => (float) $this->resource->total,
            'status' => $this->resource->status,
            'notes' => $this->resource->notes,
            'vendor' => VendorResource::make($this->whenLoaded('vendor')),
            'package' => VendorPackageResource::make($this->whenLoaded('package')),
            'items' => BookingItemResource::collection($this->whenLoaded('items')),
            'histories' => BookingHistoryResource::collection($this->whenLoaded('histories')),
            'documents' => BookingDocumentResource::collection($this->whenLoaded('documents')),
            'created_at' => $this->resource->created_at instanceof Carbon
                ? $this->resource->created_at->toIsoString()
                : null,
            'updated_at' => $this->resource->updated_at instanceof Carbon
                ? $this->resource->updated_at->toIsoString()
                : null,
        ];
    }
}
