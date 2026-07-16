'use client';

import type { VendorFilters } from '@/types';
import { useState } from 'react';

interface VendorFiltersProps {
  onApply: (filters: VendorFilters) => void;
  initialFilters?: VendorFilters;
}

export function VendorFilters({ onApply, initialFilters }: VendorFiltersProps) {
  const [search, setSearch] = useState(initialFilters?.search ?? '');
  const [category, setCategory] = useState(initialFilters?.category ?? '');
  const [minRating, setMinRating] = useState(initialFilters?.min_rating ?? '');

  const handleApply = () => {
    onApply({
      search: search || undefined,
      category: category || undefined,
      min_rating: minRating ? Number(minRating) : undefined,
    });
  };

  const handleReset = () => {
    setSearch('');
    setCategory('');
    setMinRating('');
    onApply({});
  };

  return (
    <div className="flex flex-wrap items-end gap-3">
      <div className="min-w-0 flex-1">
        <label htmlFor="vendor-search" className="block text-xs font-medium text-muted-foreground mb-1">
          Search
        </label>
        <input
          id="vendor-search"
          type="text"
          value={search}
          onChange={(e) => setSearch(e.target.value)}
          placeholder="Business name, city, province..."
          className="block w-full rounded-md border border-border bg-background px-3 py-1.5 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-primary"
        />
      </div>
      <div className="w-40">
        <label htmlFor="vendor-category" className="block text-xs font-medium text-muted-foreground mb-1">
          Category
        </label>
        <select
          id="vendor-category"
          value={category}
          onChange={(e) => setCategory(e.target.value)}
          className="block w-full rounded-md border border-border bg-background px-3 py-1.5 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-primary"
        >
          <option value="">All Categories</option>
          <option value="Dekorasi">Dekorasi</option>
          <option value="Catering">Catering</option>
          <option value="Venue">Venue</option>
          <option value="Fotografer">Fotografer</option>
          <option value="Videografer">Videografer</option>
          <option value="MC">MC</option>
          <option value="Entertainment">Entertainment</option>
          <option value="MUA">MUA</option>
          <option value="Dokumentasi">Dokumentasi</option>
        </select>
      </div>
      <div className="w-32">
        <label htmlFor="vendor-rating" className="block text-xs font-medium text-muted-foreground mb-1">
          Min Rating
        </label>
        <select
          id="vendor-rating"
          value={minRating}
          onChange={(e) => setMinRating(e.target.value)}
          className="block w-full rounded-md border border-border bg-background px-3 py-1.5 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-primary"
        >
          <option value="">Any Rating</option>
          <option value="4">4+ ★</option>
          <option value="3">3+ ★</option>
          <option value="2">2+ ★</option>
          <option value="1">1+ ★</option>
        </select>
      </div>
      <button
        type="button"
        onClick={handleApply}
        className="inline-flex h-8 shrink-0 items-center justify-center gap-1 rounded-md bg-primary px-3 text-xs font-medium text-primary-foreground transition-all hover:bg-primary/80"
      >
        Apply
      </button>
      <button
        type="button"
        onClick={handleReset}
        className="inline-flex h-8 shrink-0 items-center justify-center gap-1 rounded-md border border-border bg-background px-3 text-xs font-medium text-foreground transition-all hover:bg-muted"
      >
        Reset
      </button>
    </div>
  );
}
