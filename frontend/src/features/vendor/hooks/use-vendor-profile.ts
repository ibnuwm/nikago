'use client';

import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '@/services/api';
import type { ApiResponse, Vendor, VendorGallery, VendorPortfolio, VendorPackage, VendorService } from '@/types';

export function useVendorProfile(uuid: string | null) {
  return useQuery({
    queryKey: ['vendor-profile', uuid],
    queryFn: async () => {
      const response = await api.get<ApiResponse<Vendor>>(`/vendors/${uuid}`);
      return response.data.data;
    },
    enabled: !!uuid,
  });
}

export function useUpdateVendorProfile(uuid: string) {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (data: Partial<Vendor>) => {
      const response = await api.put<ApiResponse<Vendor>>(`/vendors/${uuid}`, data);
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['vendor-profile', uuid] });
      queryClient.invalidateQueries({ queryKey: ['vendors'] });
    },
  });
}

export function useVendorGalleries(vendorUuid: string | null) {
  return useQuery({
    queryKey: ['vendor-galleries', vendorUuid],
    queryFn: async () => {
      const response = await api.get<ApiResponse<VendorGallery[]>>(`/vendors/${vendorUuid}/galleries`);
      return response.data.data;
    },
    enabled: !!vendorUuid,
  });
}

export function useCreateVendorGallery(vendorUuid: string) {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (data: { image_url: string; caption?: string }) => {
      const response = await api.post<ApiResponse<VendorGallery>>(`/vendors/${vendorUuid}/galleries`, data);
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['vendor-galleries', vendorUuid] });
    },
  });
}

export function useDeleteVendorGallery(vendorUuid: string) {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (galleryId: number) => {
      await api.delete(`/vendors/${vendorUuid}/galleries/${galleryId}`);
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['vendor-galleries', vendorUuid] });
    },
  });
}

export function useVendorPortfolios(vendorUuid: string | null) {
  return useQuery({
    queryKey: ['vendor-portfolios', vendorUuid],
    queryFn: async () => {
      const response = await api.get<ApiResponse<VendorPortfolio[]>>(`/vendors/${vendorUuid}/portfolios`);
      return response.data.data;
    },
    enabled: !!vendorUuid,
  });
}

export function useCreateVendorPortfolio(vendorUuid: string) {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (data: { title: string; description?: string; image_url: string }) => {
      const response = await api.post<ApiResponse<VendorPortfolio>>(`/vendors/${vendorUuid}/portfolios`, data);
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['vendor-portfolios', vendorUuid] });
    },
  });
}

export function useDeleteVendorPortfolio(vendorUuid: string) {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (portfolioId: number) => {
      await api.delete(`/vendors/${vendorUuid}/portfolios/${portfolioId}`);
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['vendor-portfolios', vendorUuid] });
    },
  });
}

export function useVendorPackages(vendorUuid: string | null) {
  return useQuery({
    queryKey: ['vendor-packages', vendorUuid],
    queryFn: async () => {
      const response = await api.get<ApiResponse<VendorPackage[]>>(`/vendors/${vendorUuid}/packages`);
      return response.data.data;
    },
    enabled: !!vendorUuid,
  });
}

export function useCreateVendorPackage(vendorUuid: string) {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (data: { name: string; description?: string; price: number; inclusions?: string[] }) => {
      const response = await api.post<ApiResponse<VendorPackage>>(`/vendors/${vendorUuid}/packages`, data);
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['vendor-packages', vendorUuid] });
    },
  });
}

export function useDeleteVendorPackage(vendorUuid: string) {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (packageId: number) => {
      await api.delete(`/vendors/${vendorUuid}/packages/${packageId}`);
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['vendor-packages', vendorUuid] });
    },
  });
}

export function useVendorServices(vendorUuid: string | null) {
  return useQuery({
    queryKey: ['vendor-services', vendorUuid],
    queryFn: async () => {
      const response = await api.get<ApiResponse<VendorService[]>>(`/vendors/${vendorUuid}/services`);
      return response.data.data;
    },
    enabled: !!vendorUuid,
  });
}

export function useCreateVendorService(vendorUuid: string) {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (data: { name: string; description?: string; starting_price?: number }) => {
      const response = await api.post<ApiResponse<VendorService>>(`/vendors/${vendorUuid}/services`, data);
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['vendor-services', vendorUuid] });
    },
  });
}

export function useDeleteVendorService(vendorUuid: string) {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (serviceId: number) => {
      await api.delete(`/vendors/${vendorUuid}/services/${serviceId}`);
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['vendor-services', vendorUuid] });
    },
  });
}
