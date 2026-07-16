<?php

declare(strict_types=1);

namespace App\Modules\Vendor\Resources;

use App\Core\Base\Resource;
use App\Modules\Vendor\Models\Vendor;
use Carbon\Carbon;

/**
 * @property-read Vendor $resource
 */
class VendorResource extends Resource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->resource->uuid,
            'business_name' => $this->resource->business_name,
            'slug' => $this->resource->slug,
            'description' => $this->resource->description,
            'phone' => $this->resource->phone,
            'email' => $this->resource->email,
            'address' => $this->resource->address,
            'city' => $this->resource->city,
            'province' => $this->resource->province,
            'status' => $this->resource->status,
            'rating' => (float) $this->resource->rating,
            'total_review' => $this->resource->total_review,
            'verified_at' => $this->resource->verified_at instanceof Carbon
                ? $this->resource->verified_at->toIsoString()
                : null,
            'services' => VendorServiceResource::collection(
                $this->whenLoaded('services')
            ),
            'packages' => VendorPackageResource::collection(
                $this->whenLoaded('packages')
            ),
            'portfolios' => VendorPortfolioResource::collection(
                $this->whenLoaded('portfolios')
            ),
            'galleries' => VendorGalleryResource::collection(
                $this->whenLoaded('galleries')
            ),
            'created_at' => $this->resource->created_at instanceof Carbon
                ? $this->resource->created_at->toIsoString()
                : null,
            'updated_at' => $this->resource->updated_at instanceof Carbon
                ? $this->resource->updated_at->toIsoString()
                : null,
        ];
    }
}
