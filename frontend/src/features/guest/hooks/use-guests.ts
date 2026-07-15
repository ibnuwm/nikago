'use client';

import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '@/services/api';
import type { ApiResponse, PaginatedData, Guest, GuestFormData } from '@/types';

interface UseGuestsParams {
  page?: number;
  per_page?: number;
  search?: string;
  status?: string;
  wedding_id?: number;
  group_id?: number;
  category_id?: number;
  sort?: string;
  direction?: string;
}

export function useGuests(params?: UseGuestsParams) {
  return useQuery({
    queryKey: ['guests', params],
    queryFn: async () => {
      const searchParams = new URLSearchParams();
      if (params?.page) searchParams.set('page', String(params.page));
      if (params?.per_page) searchParams.set('per_page', String(params.per_page));
      if (params?.search) searchParams.set('search', params.search);
      if (params?.status) searchParams.set('status', params.status);
      if (params?.wedding_id) searchParams.set('wedding_id', String(params.wedding_id));
      if (params?.group_id) searchParams.set('group_id', String(params.group_id));
      if (params?.category_id) searchParams.set('category_id', String(params.category_id));
      if (params?.sort) searchParams.set('sort', params.sort);
      if (params?.direction) searchParams.set('direction', params.direction);

      const queryString = searchParams.toString();
      const url = `/guests${queryString ? `?${queryString}` : ''}`;

      const response = await api.get<ApiResponse<PaginatedData<Guest>>>(url);
      return response.data.data;
    },
  });
}

export function useGuest(uuid: string | null) {
  return useQuery({
    queryKey: ['guest', uuid],
    queryFn: async () => {
      const response = await api.get<ApiResponse<Guest>>(`/guests/${uuid}`);
      return response.data.data;
    },
    enabled: !!uuid,
  });
}

export function useCreateGuest() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (data: GuestFormData) => {
      const response = await api.post<ApiResponse<Guest>>('/guests', data);
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['guests'] });
    },
  });
}

export function useUpdateGuest() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async ({ uuid, data }: { uuid: string; data: Partial<GuestFormData> }) => {
      const response = await api.put<ApiResponse<Guest>>(`/guests/${uuid}`, data);
      return response.data.data;
    },
    onSuccess: (_, variables) => {
      queryClient.invalidateQueries({ queryKey: ['guests'] });
      queryClient.invalidateQueries({ queryKey: ['guest', variables.uuid] });
    },
  });
}

export function useDeleteGuest() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (uuid: string) => {
      await api.delete(`/guests/${uuid}`);
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['guests'] });
    },
  });
}

export function useImportGuests() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (formData: FormData) => {
      const response = await api.post<ApiResponse<{ imported: number; failed: number }>>('/guests/import', formData);
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['guests'] });
    },
  });
}

export function useExportGuests() {
  return useMutation({
    mutationFn: async () => {
      const response = await api.get<ApiResponse<string[][]>>('/guests/export');
      return response.data.data;
    },
  });
}

export function useSendInvitation() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (guestUuid: string) => {
      const response = await api.post<ApiResponse<Guest>>('/guests/send-invitation', { guest_uuid: guestUuid });
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['guests'] });
    },
  });
}
