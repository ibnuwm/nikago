'use client';

import type { Vendor } from '@/types';

interface VendorDetailProps {
  vendor: Vendor;
  onBack: () => void;
  onEdit?: (uuid: string) => void;
  onVerify?: (uuid: string) => void;
  onActivate?: (uuid: string) => void;
  onDeactivate?: (uuid: string) => void;
}

export function VendorDetail({ vendor, onBack, onEdit, onVerify, onActivate, onDeactivate }: VendorDetailProps) {
  return (
    <div className="space-y-6">
      <button
        type="button"
        onClick={onBack}
        className="inline-flex items-center gap-1 text-sm text-muted-foreground hover:text-foreground transition-colors"
      >
        ← Back to vendors
      </button>

      <div className="rounded-lg border bg-card shadow-sm">
        <div className="flex items-start justify-between border-b px-6 py-5">
          <div>
            <h2 className="text-xl font-bold text-card-foreground">{vendor.business_name}</h2>
            <div className="mt-2 flex flex-wrap items-center gap-3 text-sm text-muted-foreground">
              {vendor.city && vendor.province && (
                <span>{vendor.city}, {vendor.province}</span>
              )}
              {vendor.rating > 0 && (
                <span>★ {vendor.rating.toFixed(1)} ({vendor.total_review} reviews)</span>
              )}
              <span className={vendor.status === 'active' ? 'text-emerald-600' : 'text-muted-foreground'}>
                {vendor.status}
              </span>
              {vendor.verified_at ? (
                <span className="text-emerald-600">✓ Verified</span>
              ) : (
                <span className="text-amber-600">Unverified</span>
              )}
            </div>
          </div>
          <div className="flex items-center gap-2">
            {onEdit && (
              <button
                type="button"
                onClick={() => onEdit(vendor.id)}
                className="inline-flex h-8 items-center justify-center gap-1 rounded-md border border-border bg-background px-3 text-xs font-medium text-foreground transition-all hover:bg-muted"
              >
                Edit
              </button>
            )}
            {!vendor.verified_at && onVerify && (
              <button
                type="button"
                onClick={() => onVerify(vendor.id)}
                className="inline-flex h-8 items-center justify-center gap-1 rounded-md bg-emerald-600 px-3 text-xs font-medium text-white transition-all hover:bg-emerald-700"
              >
                Verify
              </button>
            )}
            {vendor.status === 'active' && onDeactivate ? (
              <button
                type="button"
                onClick={() => onDeactivate(vendor.id)}
                className="inline-flex h-8 items-center justify-center gap-1 rounded-md border border-amber-300 bg-amber-50 px-3 text-xs font-medium text-amber-700 transition-all hover:bg-amber-100"
              >
                Deactivate
              </button>
            ) : vendor.status === 'inactive' && onActivate ? (
              <button
                type="button"
                onClick={() => onActivate(vendor.id)}
                className="inline-flex h-8 items-center justify-center gap-1 rounded-md bg-emerald-600 px-3 text-xs font-medium text-white transition-all hover:bg-emerald-700"
              >
                Activate
              </button>
            ) : null}
          </div>
        </div>

        <div className="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2">
          <div className="space-y-4">
            <h3 className="text-sm font-semibold text-foreground">Contact Information</h3>
            <div className="space-y-2 text-sm text-muted-foreground">
              {vendor.phone && (
                <p><span className="font-medium text-foreground">Phone:</span> {vendor.phone}</p>
              )}
              {vendor.email && (
                <p><span className="font-medium text-foreground">Email:</span> {vendor.email}</p>
              )}
              {vendor.address && (
                <p><span className="font-medium text-foreground">Address:</span> {vendor.address}</p>
              )}
            </div>
          </div>

          {vendor.description && (
            <div className="space-y-2">
              <h3 className="text-sm font-semibold text-foreground">About</h3>
              <p className="text-sm text-muted-foreground">{vendor.description}</p>
            </div>
          )}

          {vendor.services && vendor.services.length > 0 && (
            <div className="sm:col-span-2 space-y-3">
              <h3 className="text-sm font-semibold text-foreground">Services</h3>
              <div className="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                {vendor.services.map((svc) => (
                  <div key={svc.id} className="rounded-md border bg-muted/30 p-3">
                    <p className="text-sm font-medium text-foreground">{svc.name}</p>
                    {svc.description && (
                      <p className="mt-1 text-xs text-muted-foreground">{svc.description}</p>
                    )}
                    {svc.starting_price && (
                      <p className="mt-1 text-xs font-medium text-emerald-600">
                        Start from Rp {svc.starting_price.toLocaleString('id-ID')}
                      </p>
                    )}
                  </div>
                ))}
              </div>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
