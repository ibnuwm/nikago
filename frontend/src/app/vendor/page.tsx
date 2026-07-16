'use client';

import { useUser } from '@/hooks/use-auth';
import {
  useVendors,
  useCreateVendor,
  useDeleteVendor,
  useVerifyVendor,
  useActivateVendor,
  useDeactivateVendor,
} from '@/features/vendor/hooks/use-vendor';
import { VendorCard } from '@/features/vendor/components/vendor-card';
import { VendorFilters } from '@/features/vendor/components/vendor-filters';
import { CreateVendorForm } from '@/features/vendor/components/create-vendor-form';
import { VendorDetail } from '@/features/vendor/components/vendor-detail';
import { useAuthStore } from '@/stores/auth-store';
import { useRouter } from 'next/navigation';
import { useEffect, useState } from 'react';
import type { VendorFilters as VendorFiltersType, Vendor } from '@/types';

type ViewState =
  | { type: 'list' }
  | { type: 'detail'; vendor: Vendor }
  | { type: 'create' };

export default function VendorPage() {
  const { data: user, isLoading: isUserLoading } = useUser();
  const token = useAuthStore((s) => s.token);
  const router = useRouter();
  const [view, setView] = useState<ViewState>({ type: 'list' });
  const [filters, setFilters] = useState<VendorFiltersType>({});

  const { data: vendorsData, isLoading: isVendorsLoading } = useVendors(filters);

  const createVendor = useCreateVendor();
  const deleteVendor = useDeleteVendor();
  const verifyVendor = useVerifyVendor();
  const activateVendor = useActivateVendor();
  const deactivateVendor = useDeactivateVendor();

  const vendors = vendorsData?.data ?? [];

  useEffect(() => {
    if (!token && !isUserLoading) {
      router.push('/login');
    }
  }, [token, isUserLoading, router]);

  if (isUserLoading || isVendorsLoading) {
    return (
      <div className="flex min-h-screen items-center justify-center">
        <p className="text-sm text-muted-foreground">Loading...</p>
      </div>
    );
  }

  if (!user) {
    return null;
  }

  const handleCreate = (data: {
    business_name: string;
    description?: string;
    phone?: string;
    email?: string;
    address?: string;
    city?: string;
    province?: string;
    services?: { name: string; description?: string; starting_price?: number }[];
  }) => {
    createVendor.mutate(data, {
      onSuccess: () => setView({ type: 'list' }),
    });
  };

  return (
    <div className="min-h-screen bg-background">
      <header className="border-b bg-card">
        <div className="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
          <h1 className="text-xl font-bold text-card-foreground">Vendor Directory</h1>
          <div className="flex items-center gap-4">
            <span className="text-sm text-muted-foreground">{user.name}</span>
            <a
              href="/dashboard"
              className="inline-flex h-7 shrink-0 items-center justify-center gap-1 rounded-[min(var(--radius-md),12px)] border border-border bg-background px-2.5 text-[0.8rem] font-medium whitespace-nowrap text-foreground transition-all outline-none select-none hover:bg-muted hover:text-foreground"
            >
              Back to Dashboard
            </a>
          </div>
        </div>
      </header>

      <main className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        {view.type === 'detail' ? (
          <VendorDetail
            vendor={view.vendor}
            onBack={() => setView({ type: 'list' })}
            onEdit={(uuid) => {
              const vendor = vendors.find((v) => v.id === uuid);
              if (vendor) setView({ type: 'detail', vendor });
            }}
            onVerify={(uuid) => verifyVendor.mutate(uuid, {
              onSuccess: () => setView({ type: 'list' }),
            })}
            onActivate={(uuid) => activateVendor.mutate(uuid, {
              onSuccess: () => setView({ type: 'list' }),
            })}
            onDeactivate={(uuid) => deactivateVendor.mutate(uuid, {
              onSuccess: () => setView({ type: 'list' }),
            })}
          />
        ) : (
          <>
            <div className="flex items-center justify-between">
              <div>
                <h2 className="text-2xl font-bold text-foreground">All Vendors</h2>
                <p className="mt-1 text-sm text-muted-foreground">
                  Browse and manage wedding vendors.
                </p>
              </div>
              <button
                type="button"
                onClick={() => setView({ type: 'create' })}
                className="inline-flex h-7 shrink-0 items-center justify-center gap-1 rounded-[min(var(--radius-md),12px)] bg-primary px-2.5 text-[0.8rem] font-medium whitespace-nowrap text-primary-foreground transition-all outline-none select-none hover:bg-primary/80"
              >
                Add Vendor
              </button>
            </div>

            <div className="mt-6">
              <VendorFilters onApply={setFilters} />
            </div>

            <div className="mt-8 space-y-4">
              {view.type === 'create' && (
                <CreateVendorForm
                  weddingId={1}
                  onSubmit={handleCreate}
                  onCancel={() => setView({ type: 'list' })}
                />
              )}

              {vendors.length > 0 ? (
                vendors.map((vendor) => (
                  <VendorCard
                    key={vendor.id}
                    vendor={vendor}
                    onClick={(uuid) => {
                      const found = vendors.find((v) => v.id === uuid);
                      if (found) setView({ type: 'detail', vendor: found });
                    }}
                    onEdit={(uuid) => {
                      const found = vendors.find((v) => v.id === uuid);
                      if (found) setView({ type: 'detail', vendor: found });
                    }}
                    onDelete={(uuid) => deleteVendor.mutate(uuid)}
                    onVerify={(uuid) => verifyVendor.mutate(uuid)}
                    onActivate={(uuid) => activateVendor.mutate(uuid)}
                    onDeactivate={(uuid) => deactivateVendor.mutate(uuid)}
                  />
                ))
              ) : (
                <div className="rounded-lg border bg-card p-12 text-center shadow-sm">
                  <p className="text-muted-foreground">
                    No vendors found. Add your first vendor or adjust your filters.
                  </p>
                </div>
              )}
            </div>
          </>
        )}
      </main>
    </div>
  );
}
