'use client';

import { useState, useEffect } from 'react';
import { useRouter } from 'next/navigation';
import { useAuthStore } from '@/stores/auth-store';
import { useUser } from '@/hooks/use-auth';
import {
  useMarketplaceVendors,
  useMarketplaceCategories,
  useSearchMarketplace,
  usePopularVendors,
  useRecommendedVendors,
  useWishlists,
  useAddToWishlist,
  useRemoveFromWishlist,
  useCompareVendors,
} from '@/features/marketplace/hooks/use-marketplace';
import { MarketplaceCard } from '@/features/marketplace/components/marketplace-card';
import { FeaturedVendors } from '@/features/marketplace/components/featured-vendors';
import type { MarketplaceFilters } from '@/types';

type Tab = 'vendors' | 'popular' | 'recommended' | 'wishlist';

export default function MarketplacePage() {
  const { data: user, isLoading: isUserLoading } = useUser();
  const token = useAuthStore((s) => s.token);
  const router = useRouter();
  const [activeTab, setActiveTab] = useState<Tab>('vendors');
  const [search, setSearch] = useState('');
  const [filters, setFilters] = useState<MarketplaceFilters>({});
  const [compareList, setCompareList] = useState<string[]>([]);

  const { data: vendorsData, isLoading: isVendorsLoading } = useMarketplaceVendors(
    search ? undefined : filters
  );
  const { data: searchData, isLoading: isSearchLoading } = useSearchMarketplace(search, search ? filters : undefined);
  const { data: categories } = useMarketplaceCategories();
  const { data: popularData } = usePopularVendors();
  const { data: recommendedData } = useRecommendedVendors();
  const { data: wishlists, isLoading: isWishlistLoading } = useWishlists();
  const addToWishlist = useAddToWishlist();
  const removeFromWishlist = useRemoveFromWishlist();
  const compareVendors = useCompareVendors();

  const vendors = search ? (searchData?.data ?? []) : (vendorsData?.data ?? []);
  const isLoading = isUserLoading || isVendorsLoading || isSearchLoading || isWishlistLoading;

  const wishlistedIds = new Set((wishlists ?? []).map((v) => v.id));

  useEffect(() => {
    if (!token && !isUserLoading) {
      router.push('/login');
    }
  }, [token, isUserLoading, router]);

  const handleToggleWishlist = (vendorUuid: string) => {
    if (wishlistedIds.has(vendorUuid)) {
      const wishlist = wishlists?.find((v) => v.id === vendorUuid);
      if (wishlist) {
        removeFromWishlist.mutate(wishlist.id);
      }
    } else {
      addToWishlist.mutate(vendorUuid);
    }
  };

  const handleToggleCompare = (vendorUuid: string) => {
    setCompareList((prev) =>
      prev.includes(vendorUuid)
        ? prev.filter((id) => id !== vendorUuid)
        : [...prev, vendorUuid]
    );
  };

  const handleCompare = () => {
    if (compareList.length >= 2) {
      compareVendors.mutate(compareList);
    }
  };

  if (isUserLoading) {
    return (
      <div className="flex min-h-screen items-center justify-center bg-background">
        <p className="text-sm text-muted-foreground">Loading...</p>
      </div>
    );
  }

  if (!user) {
    return null;
  }

  const renderVendorList = (vendorList: typeof vendors) => (
    <div className="space-y-4">
      {vendorList.length > 0 ? (
        vendorList.map((vendor) => (
          <MarketplaceCard
            key={vendor.id}
            vendor={vendor}
            isWishlisted={wishlistedIds.has(vendor.id)}
            onToggleWishlist={handleToggleWishlist}
            onCompare={handleToggleCompare}
            isSelected={compareList.includes(vendor.id)}
            onClick={(uuid) => router.push(`/vendor/${uuid}`)}
          />
        ))
      ) : (
        <div className="rounded-lg border bg-card p-12 text-center shadow-sm">
          <p className="text-muted-foreground">
            {search ? 'No vendors match your search.' : 'No vendors found.'}
          </p>
        </div>
      )}
    </div>
  );

  const tabContent = () => {
    switch (activeTab) {
      case 'vendors':
        return (
          <>
            <FeaturedVendors />

            <div className="mt-8">
              <div className="flex items-center justify-between">
                <h2 className="text-xl font-bold text-foreground">All Vendors</h2>
                <div className="flex items-center gap-2">
                  <select
                    value={filters.sort ?? ''}
                    onChange={(e) => setFilters((prev) => ({ ...prev, sort: e.target.value || undefined }))}
                    className="h-8 rounded border border-border bg-background px-2 text-xs text-foreground"
                  >
                    <option value="">Sort by</option>
                    <option value="popular">Popular</option>
                    <option value="rating">Rating</option>
                    <option value="lowest_price">Lowest Price</option>
                    <option value="highest_price">Highest Price</option>
                    <option value="newest">Newest</option>
                  </select>
                </div>
              </div>
            </div>

            <div className="mt-4 flex flex-wrap gap-2">
              <input
                type="text"
                placeholder="Search vendors..."
                value={search}
                onChange={(e) => setSearch(e.target.value)}
                className="h-8 flex-1 min-w-[200px] rounded border border-border bg-background px-2 text-xs text-foreground"
              />
              <select
                value={filters.category ?? ''}
                onChange={(e) => setFilters((prev) => ({ ...prev, category: e.target.value || undefined }))}
                className="h-8 rounded border border-border bg-background px-2 text-xs text-foreground"
              >
                <option value="">All Categories</option>
                {categories?.map((cat) => (
                  <option key={cat.name} value={cat.name}>
                    {cat.name} ({cat.vendor_count})
                  </option>
                ))}
              </select>
              <select
                value={filters.city ?? ''}
                onChange={(e) => setFilters((prev) => ({ ...prev, city: e.target.value || undefined }))}
                className="h-8 rounded border border-border bg-background px-2 text-xs text-foreground"
              >
                <option value="">All Cities</option>
                <option value="Jakarta">Jakarta</option>
                <option value="Bandung">Bandung</option>
                <option value="Surabaya">Surabaya</option>
                <option value="Yogyakarta">Yogyakarta</option>
                <option value="Bali">Bali</option>
              </select>
              <select
                value={filters.min_rating ?? ''}
                onChange={(e) => setFilters((prev) => ({ ...prev, min_rating: e.target.value ? Number(e.target.value) : undefined }))}
                className="h-8 rounded border border-border bg-background px-2 text-xs text-foreground"
              >
                <option value="">Min Rating</option>
                <option value="4">4+ ★</option>
                <option value="3">3+ ★</option>
                <option value="2">2+ ★</option>
              </select>
            </div>

            {compareList.length >= 2 && (
              <div className="mt-4 rounded-lg border bg-muted p-3">
                <p className="text-xs text-muted-foreground">
                  {compareList.length} vendors selected for comparison.
                </p>
                <div className="mt-2 flex gap-2">
                  <button
                    type="button"
                    onClick={handleCompare}
                    className="inline-flex h-7 items-center justify-center rounded bg-primary px-3 text-xs text-primary-foreground hover:bg-primary/80"
                  >
                    Compare Now
                  </button>
                  <button
                    type="button"
                    onClick={() => setCompareList([])}
                    className="inline-flex h-7 items-center justify-center rounded border border-border bg-background px-3 text-xs text-foreground hover:bg-muted"
                  >
                    Clear
                  </button>
                </div>
              </div>
            )}

            {isLoading ? (
              <div className="mt-8 flex justify-center">
                <p className="text-sm text-muted-foreground">Loading vendors...</p>
              </div>
            ) : (
              <div className="mt-6">{renderVendorList(vendors)}</div>
            )}
          </>
        );
      case 'popular':
        return (
          <>
            <h2 className="text-xl font-bold text-foreground">Popular Vendors</h2>
            <p className="mt-1 text-sm text-muted-foreground">
              Most reviewed and highest rated vendors.
            </p>
            <div className="mt-4">{renderVendorList(popularData ?? [])}</div>
          </>
        );
      case 'recommended':
        return (
          <>
            <h2 className="text-xl font-bold text-foreground">Recommended Vendors</h2>
            <p className="mt-1 text-sm text-muted-foreground">
              Top-rated vendors recommended for you.
            </p>
            <div className="mt-4">{renderVendorList(recommendedData ?? [])}</div>
          </>
        );
      case 'wishlist':
        return (
          <>
            <h2 className="text-xl font-bold text-foreground">My Wishlist</h2>
            <p className="mt-1 text-sm text-muted-foreground">
              Your favorite vendors saved for later.
            </p>
            <div className="mt-4">{renderVendorList(wishlists ?? [])}</div>
          </>
        );
    }
  };

  return (
    <div className="min-h-screen bg-background">
      <header className="border-b bg-card">
        <div className="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
          <h1 className="text-xl font-bold text-card-foreground">Vendor Marketplace</h1>
          <div className="flex items-center gap-4">
            <span className="text-sm text-muted-foreground">{user.name}</span>
            <a
              href="/dashboard"
              className="inline-flex h-7 shrink-0 items-center justify-center gap-1 rounded-[min(var(--radius-md),12px)] border border-border bg-background px-2.5 text-[0.8rem] font-medium whitespace-nowrap text-foreground transition-all outline-none select-none hover:bg-muted hover:text-foreground"
            >
              Dashboard
            </a>
          </div>
        </div>
      </header>

      <main className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div className="border-b border-border">
          <nav className="-mb-px flex gap-4">
            {([
              { key: 'vendors', label: 'All Vendors' },
              { key: 'popular', label: 'Popular' },
              { key: 'recommended', label: 'Recommended' },
              { key: 'wishlist', label: 'Wishlist' },
            ] as const).map((tab) => (
              <button
                key={tab.key}
                type="button"
                onClick={() => setActiveTab(tab.key)}
                className={`pb-3 text-sm font-medium transition-colors ${
                  activeTab === tab.key
                    ? 'border-b-2 border-primary text-primary'
                    : 'text-muted-foreground hover:text-foreground'
                }`}
              >
                {tab.label}
              </button>
            ))}
          </nav>
        </div>

        <div className="mt-6">{tabContent()}</div>
      </main>
    </div>
  );
}
