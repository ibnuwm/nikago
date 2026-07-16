'use client';

import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '@/services/api';
import type {
  ApiResponse,
  PaginatedData,
  Vendor,
  VendorFormData,
  VendorStatistics,
  VendorFilters,
} from '@/types';

export function useVendors(filters?: VendorFilters) {
  return useQuery({
    queryKey: ['vendors', filters],
    queryFn: async () => {
      const params = new URLSearchParams();
      if (filters?.search) params.set('search', filters.search);
      if (filters?.category) params.set('category', filters.category);
      if (filters?.verified !== undefined) params.set('verified', filters.verified ? '1' : '0');
      if (filters?.min_rating !== undefined) params.set('min_rating', String(filters.min_rating));
      if (filters?.sort) params.set('sort', filters.sort);
      if (filters?.direction) params.set('direction', filters.direction);
      const qs = params.toString();
      const url = `/vendors${qs ? `?${qs}` : ''}`;
      const response = await api.get<ApiResponse<PaginatedData<Vendor>>>(url);
      return response.data.data;
    },
  });
}

export function useVendor(uuid: string | null) {
  return useQuery({
    queryKey: ['vendor', uuid],
    queryFn: async () => {
      const response = await api.get<ApiResponse<Vendor>>(`/vendors/${uuid}`);
      return response.data.data;
    },
    enabled: !!uuid,
  });
}

export function useCreateVendor() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (data: VendorFormData) => {
      const response = await api.post<ApiResponse<Vendor>>('/vendors', data);
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['vendors'] });
    },
  });
}

export function useUpdateVendor() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async ({ uuid, data }: { uuid: string; data: Partial<VendorFormData> }) => {
      const response = await api.put<ApiResponse<Vendor>>(`/vendors/${uuid}`, data);
      return response.data.data;
    },
    onSuccess: (_, variables) => {
      queryClient.invalidateQueries({ queryKey: ['vendors'] });
      queryClient.invalidateQueries({ queryKey: ['vendor', variables.uuid] });
    },
  });
}

export function useDeleteVendor() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (uuid: string) => {
      await api.delete(`/vendors/${uuid}`);
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['vendors'] });
    },
  });
}

export function useVerifyVendor() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (uuid: string) => {
      const response = await api.patch<ApiResponse<Vendor>>(`/vendors/${uuid}/verify`);
      return response.data.data;
    },
    onSuccess: (_, uuid) => {
      queryClient.invalidateQueries({ queryKey: ['vendors'] });
      queryClient.invalidateQueries({ queryKey: ['vendor', uuid] });
    },
  });
}

export function useActivateVendor() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (uuid: string) => {
      const response = await api.patch<ApiResponse<Vendor>>(`/vendors/${uuid}/activate`);
      return response.data.data;
    },
    onSuccess: (_, uuid) => {
      queryClient.invalidateQueries({ queryKey: ['vendors'] });
      queryClient.invalidateQueries({ queryKey: ['vendor', uuid] });
    },
  });
}

export function useDeactivateVendor() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (uuid: string) => {
      const response = await api.patch<ApiResponse<Vendor>>(`/vendors/${uuid}/deactivate`);
      return response.data.data;
    },
    onSuccess: (_, uuid) => {
      queryClient.invalidateQueries({ queryKey: ['vendors'] });
      queryClient.invalidateQueries({ queryKey: ['vendor', uuid] });
    },
  });
}

export function useVendorStatistics(uuid: string | null) {
  return useQuery({
    queryKey: ['vendor-statistics', uuid],
    queryFn: async () => {
      const response = await api.get<ApiResponse<VendorStatistics>>(`/vendors/${uuid}/statistics`);
      return response.data.data;
    },
    enabled: !!uuid,
  });
}
