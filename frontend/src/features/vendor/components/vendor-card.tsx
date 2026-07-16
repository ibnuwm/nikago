'use client';

import type { Vendor } from '@/types';

interface VendorCardProps {
  vendor: Vendor;
  onEdit?: (uuid: string) => void;
  onDelete?: (uuid: string) => void;
  onVerify?: (uuid: string) => void;
  onActivate?: (uuid: string) => void;
  onDeactivate?: (uuid: string) => void;
  onClick?: (uuid: string) => void;
}

export function VendorCard({ vendor, onEdit, onDelete, onVerify, onActivate, onDeactivate, onClick }: VendorCardProps) {
  return (
    <div className="rounded-lg border bg-card shadow-sm">
      <div className="flex items-start justify-between border-b px-5 py-4">
        <div className="flex-1 min-w-0">
          <button
            type="button"
            onClick={() => onClick?.(vendor.id)}
            className="text-left"
          >
            <h3 className="text-base font-semibold text-card-foreground hover:text-primary transition-colors">
              {vendor.business_name}
            </h3>
          </button>
          {vendor.description && (
            <p className="mt-0.5 text-sm text-muted-foreground line-clamp-2">{vendor.description}</p>
          )}
          <div className="mt-2 flex flex-wrap items-center gap-2 text-xs text-muted-foreground">
            {vendor.city && <span>{vendor.city}</span>}
            {vendor.province && <span>{vendor.province}</span>}
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
            <span className={`inline-flex items-center gap-1 ${
              vendor.status === 'active' ? 'text-emerald-600' : 'text-muted-foreground'
            }`}>
              {vendor.status}
            </span>
          </div>
        </div>
        <div className="flex items-center gap-1 ml-4 shrink-0">
          {onEdit && (
            <button
              type="button"
              onClick={() => onEdit(vendor.id)}
              className="text-xs text-muted-foreground hover:text-foreground transition-colors px-2 py-1"
            >
              Edit
            </button>
          )}
          {!vendor.verified_at && onVerify && (
            <button
              type="button"
              onClick={() => onVerify(vendor.id)}
              className="text-xs text-emerald-600 hover:text-emerald-700 transition-colors px-2 py-1"
            >
              Verify
            </button>
          )}
          {vendor.status === 'active' && onDeactivate ? (
            <button
              type="button"
              onClick={() => onDeactivate(vendor.id)}
              className="text-xs text-amber-600 hover:text-amber-700 transition-colors px-2 py-1"
            >
              Deactivate
            </button>
          ) : vendor.status === 'inactive' && onActivate ? (
            <button
              type="button"
              onClick={() => onActivate(vendor.id)}
              className="text-xs text-emerald-600 hover:text-emerald-700 transition-colors px-2 py-1"
            >
              Activate
            </button>
          ) : null}
          {onDelete && (
            <button
              type="button"
              onClick={() => onDelete(vendor.id)}
              className="text-xs text-red-500 hover:text-red-700 transition-colors px-2 py-1"
            >
              Delete
            </button>
          )}
        </div>
      </div>

      {vendor.services && vendor.services.length > 0 && (
        <div className="px-5 py-3 space-y-1">
          <p className="text-xs font-medium text-muted-foreground">Services</p>
          <div className="flex flex-wrap gap-1.5">
            {vendor.services.map((svc) => (
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
          </div>
        </div>
      )}
    </div>
  );
}
