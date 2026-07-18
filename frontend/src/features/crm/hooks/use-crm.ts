'use client';

import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '@/services/api';
import type { ApiResponse, PaginatedData, Lead, Pipeline, CrmStatistics } from '@/types';

interface UseLeadsParams {
  page?: number;
  per_page?: number;
  stage?: string;
  search?: string;
  source?: string;
}

export function useLeads(params?: UseLeadsParams) {
  return useQuery({
    queryKey: ['crm', 'leads', params],
    queryFn: async () => {
      const searchParams = new URLSearchParams();
      if (params?.page) searchParams.set('page', String(params.page));
      if (params?.per_page) searchParams.set('per_page', String(params.per_page));
      if (params?.stage) searchParams.set('stage', params.stage);
      if (params?.search) searchParams.set('search', params.search);
      if (params?.source) searchParams.set('source', params.source);
      const queryString = searchParams.toString();
      const url = `/crm/leads${queryString ? `?${queryString}` : ''}`;
      const response = await api.get<ApiResponse<PaginatedData<Lead>>>(url);
      return response.data.data;
    },
  });
}

export function useLead(uuid: string | null) {
  return useQuery({
    queryKey: ['crm', 'lead', uuid],
    queryFn: async () => {
      const response = await api.get<ApiResponse<Lead>>(`/crm/leads/${uuid}`);
      return response.data.data;
    },
    enabled: !!uuid,
  });
}

export function useCreateLead() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (data: Record<string, unknown>) => {
      const response = await api.post<ApiResponse<Lead>>('/crm/leads', data);
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['crm', 'leads'] });
    },
  });
}

export function useUpdateLead() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async ({ uuid, data }: { uuid: string; data: Record<string, unknown> }) => {
      const response = await api.put<ApiResponse<Lead>>(`/crm/leads/${uuid}`, data);
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['crm', 'leads'] });
      queryClient.invalidateQueries({ queryKey: ['crm', 'lead'] });
    },
  });
}

export function useDeleteLead() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (uuid: string) => {
      await api.delete(`/crm/leads/${uuid}`);
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['crm', 'leads'] });
    },
  });
}

export function useAssignLead() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async ({ uuid, assigned_to }: { uuid: string; assigned_to: number }) => {
      const response = await api.patch<ApiResponse<Lead>>(`/crm/leads/${uuid}/assign`, { assigned_to });
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['crm', 'leads'] });
      queryClient.invalidateQueries({ queryKey: ['crm', 'lead'] });
    },
  });
}

export function useMoveStage() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async ({ uuid, stage }: { uuid: string; stage: string }) => {
      const response = await api.patch<ApiResponse<Lead>>(`/crm/leads/${uuid}/move-stage`, { stage });
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['crm', 'leads'] });
      queryClient.invalidateQueries({ queryKey: ['crm', 'lead'] });
      queryClient.invalidateQueries({ queryKey: ['crm', 'pipelines'] });
    },
  });
}

export function useCreateFollowUp() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async ({ uuid, data }: { uuid: string; data: Record<string, unknown> }) => {
      const response = await api.post<ApiResponse<Lead>>(`/crm/leads/${uuid}/follow-up`, data);
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['crm', 'lead'] });
    },
  });
}

export function usePipelines() {
  return useQuery({
    queryKey: ['crm', 'pipelines'],
    queryFn: async () => {
      const response = await api.get<ApiResponse<Pipeline[]>>('/crm/pipelines');
      return response.data.data;
    },
  });
}

export function useCrmStatistics() {
  return useQuery({
    queryKey: ['crm', 'statistics'],
    queryFn: async () => {
      const response = await api.get<ApiResponse<CrmStatistics>>('/crm/statistics');
      return response.data.data;
    },
  });
}
