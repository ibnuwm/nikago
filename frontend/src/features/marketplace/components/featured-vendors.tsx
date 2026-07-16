'use client';

import { useFeaturedVendors } from '../hooks/use-marketplace';
import { MarketplaceCard } from './marketplace-card';
import { useRouter } from 'next/navigation';

export function FeaturedVendors() {
  const { data: vendors, isLoading } = useFeaturedVendors();
  const router = useRouter();

  if (isLoading) {
    return (
      <div className="flex items-center justify-center py-8">
        <p className="text-sm text-muted-foreground">Loading...</p>
      </div>
    );
  }

  if (!vendors || vendors.length === 0) {
    return null;
  }

  return (
    <section>
      <h2 className="text-xl font-bold text-foreground">Featured Vendors</h2>
      <p className="mt-1 text-sm text-muted-foreground">
        Hand-picked vendors for your special day.
      </p>
      <div className="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        {vendors.slice(0, 4).map((vendor) => (
          <MarketplaceCard
            key={vendor.id}
            vendor={vendor}
            onClick={(uuid) => router.push(`/vendor/${uuid}`)}
          />
        ))}
      </div>
    </section>
  );
}
