'use client';

import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '@/services/api';
import type {
  ApiResponse,
  PaginatedData,
  Vendor,
  Category,
  MarketplaceFilters,
} from '@/types';

export function useMarketplaceVendors(filters?: MarketplaceFilters) {
  return useQuery({
    queryKey: ['marketplace-vendors', filters],
    queryFn: async () => {
      const params = new URLSearchParams();
      if (filters?.category) params.set('category', filters.category);
      if (filters?.city) params.set('city', filters.city);
      if (filters?.min_rating !== undefined) params.set('min_rating', String(filters.min_rating));
      if (filters?.verified !== undefined) params.set('verified', filters.verified ? '1' : '0');
      if (filters?.min_price !== undefined) params.set('min_price', String(filters.min_price));
      if (filters?.max_price !== undefined) params.set('max_price', String(filters.max_price));
      if (filters?.sort) params.set('sort', filters.sort);
      if (filters?.direction) params.set('direction', filters.direction);
      if (filters?.per_page) params.set('per_page', String(filters.per_page));
      const qs = params.toString();
      const url = `/marketplace/vendors${qs ? `?${qs}` : ''}`;
      const response = await api.get<ApiResponse<PaginatedData<Vendor>>>(url);
      return response.data.data;
    },
  });
}

export function useMarketplaceVendor(uuid: string | null) {
  return useQuery({
    queryKey: ['marketplace-vendor', uuid],
    queryFn: async () => {
      const response = await api.get<ApiResponse<Vendor>>(`/marketplace/vendors/${uuid}`);
      return response.data.data;
    },
    enabled: !!uuid,
  });
}

export function useSearchMarketplace(search: string, filters?: MarketplaceFilters) {
  return useQuery({
    queryKey: ['marketplace-search', search, filters],
    queryFn: async () => {
      const params = new URLSearchParams();
      if (search) params.set('search', search);
      if (filters?.category) params.set('category', filters.category);
      if (filters?.city) params.set('city', filters.city);
      if (filters?.min_rating !== undefined) params.set('min_rating', String(filters.min_rating));
      if (filters?.verified !== undefined) params.set('verified', filters.verified ? '1' : '0');
      if (filters?.min_price !== undefined) params.set('min_price', String(filters.min_price));
      if (filters?.max_price !== undefined) params.set('max_price', String(filters.max_price));
      if (filters?.sort) params.set('sort', filters.sort);
      if (filters?.direction) params.set('direction', filters.direction);
      const qs = params.toString();
      const url = `/marketplace/search${qs ? `?${qs}` : ''}`;
      const response = await api.get<ApiResponse<PaginatedData<Vendor>>>(url);
      return response.data.data;
    },
    enabled: search.length > 0 || Object.keys(filters ?? {}).length > 0,
  });
}

export function useMarketplaceCategories() {
  return useQuery({
    queryKey: ['marketplace-categories'],
    queryFn: async () => {
      const response = await api.get<ApiResponse<Category[]>>('/marketplace/categories');
      return response.data.data;
    },
  });
}

export function usePopularVendors() {
  return useQuery({
    queryKey: ['marketplace-popular'],
    queryFn: async () => {
      const response = await api.get<ApiResponse<Vendor[]>>('/marketplace/popular');
      return response.data.data;
    },
  });
}

export function useRecommendedVendors() {
  return useQuery({
    queryKey: ['marketplace-recommended'],
    queryFn: async () => {
      const response = await api.get<ApiResponse<Vendor[]>>('/marketplace/recommended');
      return response.data.data;
    },
  });
}

export function useFeaturedVendors() {
  return useQuery({
    queryKey: ['marketplace-featured'],
    queryFn: async () => {
      const response = await api.get<ApiResponse<Vendor[]>>('/marketplace/featured');
      return response.data.data;
    },
  });
}

export function useWishlists() {
  return useQuery({
    queryKey: ['marketplace-wishlists'],
    queryFn: async () => {
      const response = await api.get<ApiResponse<Vendor[]>>('/marketplace/wishlists');
      return response.data.data;
    },
  });
}

export function useAddToWishlist() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (vendorUuid: string) => {
      const response = await api.post<ApiResponse<{ message: string }>>('/marketplace/wishlist', {
        vendor_uuid: vendorUuid,
      });
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['marketplace-wishlists'] });
      queryClient.invalidateQueries({ queryKey: ['marketplace-vendor'] });
    },
  });
}

export function useRemoveFromWishlist() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (wishlistUuid: string) => {
      await api.delete(`/marketplace/wishlist/${wishlistUuid}`);
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['marketplace-wishlists'] });
      queryClient.invalidateQueries({ queryKey: ['marketplace-vendor'] });
    },
  });
}

export function useCompareVendors() {
  return useMutation({
    mutationFn: async (vendorUuids: string[]) => {
      const response = await api.post<ApiResponse<Vendor[]>>('/marketplace/compare', {
        vendor_uuids: vendorUuids,
      });
      return response.data.data;
    },
  });
}
