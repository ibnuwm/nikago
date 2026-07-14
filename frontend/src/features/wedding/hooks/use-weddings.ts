import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '@/services/api';
import type { ApiResponse, PaginatedData, Wedding, WeddingFormData } from '@/types';

interface UseWeddingsParams {
  page?: number;
  per_page?: number;
  search?: string;
  status?: string;
  sort?: string;
  direction?: string;
}

export function useWeddings(params?: UseWeddingsParams) {
  return useQuery({
    queryKey: ['weddings', params],
    queryFn: async () => {
      const searchParams = new URLSearchParams();
      if (params?.page) searchParams.set('page', String(params.page));
      if (params?.per_page) searchParams.set('per_page', String(params.per_page));
      if (params?.search) searchParams.set('search', params.search);
      if (params?.status) searchParams.set('status', params.status);
      if (params?.sort) searchParams.set('sort', params.sort);
      if (params?.direction) searchParams.set('direction', params.direction);

      const queryString = searchParams.toString();
      const url = `/weddings${queryString ? `?${queryString}` : ''}`;

      const response = await api.get<ApiResponse<PaginatedData<Wedding>>>(url);
      return response.data.data;
    },
  });
}

export function useWedding(uuid: string | null) {
  return useQuery({
    queryKey: ['wedding', uuid],
    queryFn: async () => {
      const response = await api.get<ApiResponse<Wedding>>(`/weddings/${uuid}`);
      return response.data.data;
    },
    enabled: !!uuid,
  });
}

export function useCreateWedding() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (data: WeddingFormData) => {
      const response = await api.post<ApiResponse<Wedding>>('/weddings', data);
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['weddings'] });
    },
  });
}

export function useUpdateWedding() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async ({ uuid, data }: { uuid: string; data: WeddingFormData }) => {
      const response = await api.put<ApiResponse<Wedding>>(`/weddings/${uuid}`, data);
      return response.data.data;
    },
    onSuccess: (_, variables) => {
      queryClient.invalidateQueries({ queryKey: ['weddings'] });
      queryClient.invalidateQueries({ queryKey: ['wedding', variables.uuid] });
    },
  });
}

export function useDeleteWedding() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (uuid: string) => {
      await api.delete(`/weddings/${uuid}`);
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['weddings'] });
    },
  });
}

export function usePublishWedding() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (uuid: string) => {
      const response = await api.patch<ApiResponse<Wedding>>(`/weddings/${uuid}/publish`);
      return response.data.data;
    },
    onSuccess: (_, uuid) => {
      queryClient.invalidateQueries({ queryKey: ['weddings'] });
      queryClient.invalidateQueries({ queryKey: ['wedding', uuid] });
    },
  });
}

export function useArchiveWedding() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (uuid: string) => {
      const response = await api.patch<ApiResponse<Wedding>>(`/weddings/${uuid}/archive`);
      return response.data.data;
    },
    onSuccess: (_, uuid) => {
      queryClient.invalidateQueries({ queryKey: ['weddings'] });
      queryClient.invalidateQueries({ queryKey: ['wedding', uuid] });
    },
  });
}
