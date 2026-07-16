'use client';

import type { Vendor } from '@/types';

interface MarketplaceCardProps {
  vendor: Vendor;
  isWishlisted?: boolean;
  onToggleWishlist?: (uuid: string) => void;
  onCompare?: (uuid: string) => void;
  isSelected?: boolean;
  onClick?: (uuid: string) => void;
}

export function MarketplaceCard({
  vendor,
  isWishlisted,
  onToggleWishlist,
  onCompare,
  isSelected,
  onClick,
}: MarketplaceCardProps) {
  return (
    <div className={`rounded-lg border bg-card shadow-sm transition-all hover:shadow-md ${
      isSelected ? 'ring-2 ring-primary' : ''
    }`}>
      {vendor.cover && (
        <div
          className="h-32 rounded-t-lg bg-cover bg-center"
          style={{ backgroundImage: `url(${vendor.cover})` }}
        />
      )}
      <div className="p-4">
        <div className="flex items-start justify-between">
          <button
            type="button"
            onClick={() => onClick?.(vendor.id)}
            className="text-left flex-1 min-w-0"
          >
            <div className="flex items-center gap-2">
              {vendor.logo && (
                <img
                  src={vendor.logo}
                  alt={vendor.business_name}
                  className="h-8 w-8 rounded-full object-cover"
                />
              )}
              <h3 className="text-base font-semibold text-card-foreground hover:text-primary transition-colors truncate">
                {vendor.business_name}
              </h3>
            </div>
          </button>
          <div className="flex items-center gap-1 ml-2 shrink-0">
            {onToggleWishlist && (
              <button
                type="button"
                onClick={() => onToggleWishlist(vendor.id)}
                className={`text-lg transition-colors ${
                  isWishlisted ? 'text-red-500' : 'text-muted-foreground hover:text-red-500'
                }`}
                title={isWishlisted ? 'Remove from wishlist' : 'Add to wishlist'}
              >
                {isWishlisted ? '♥' : '♡'}
              </button>
            )}
            {onCompare && (
              <button
                type="button"
                onClick={() => onCompare(vendor.id)}
                className={`text-xs transition-colors px-2 py-1 rounded ${
                  isSelected
                    ? 'bg-primary text-primary-foreground'
                    : 'text-muted-foreground hover:text-foreground border border-border'
                }`}
              >
                Compare
              </button>
            )}
          </div>
        </div>

        {vendor.featured && (
          <span className="mt-2 inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-xs text-amber-700">
            Featured
          </span>
        )}

        {vendor.description && (
          <p className="mt-2 text-sm text-muted-foreground line-clamp-2">{vendor.description}</p>
        )}

        <div className="mt-2 flex flex-wrap items-center gap-2 text-xs text-muted-foreground">
          {vendor.city && <span>{vendor.city}</span>}
          {vendor.rating > 0 && (
            <span className="inline-flex items-center gap-1">
              ★ {vendor.rating.toFixed(1)}
              {vendor.total_review > 0 && <>({vendor.total_review})</>}
            </span>
          )}
          {vendor.verified_at && (
            <span className="inline-flex items-center gap-1 text-emerald-600">
              ✓ Verified
            </span>
          )}
        </div>

        {vendor.services && vendor.services.length > 0 && (
          <div className="mt-3 flex flex-wrap gap-1.5">
            {vendor.services.slice(0, 3).map((svc) => (
              <span
                key={svc.id}
                className="inline-flex items-center rounded-full bg-muted px-2 py-0.5 text-xs text-muted-foreground"
              >
                {svc.name}
                {svc.starting_price && (
                  <span className="ml-1 text-emerald-600">
                    Rp {svc.starting_price.toLocaleString('id-ID')}
                  </span>
                )}
              </span>
            ))}
            {vendor.services.length > 3 && (
              <span className="text-xs text-muted-foreground">
                +{vendor.services.length - 3} more
              </span>
            )}
          </div>
        )}
      </div>
    </div>
  );
}
