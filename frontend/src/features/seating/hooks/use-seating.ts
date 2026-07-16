'use client';

import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '@/services/api';
import type {
  ApiResponse,
  PaginatedData,
  SeatingTable,
  SeatingTableFormData,
  SeatingPreview,
  SeatAssignmentFormData,
} from '@/types';

export function useSeatingTables(params?: { wedding_id?: number; page?: number }) {
  return useQuery({
    queryKey: ['seating-tables', params],
    queryFn: async () => {
      const searchParams = new URLSearchParams();
      if (params?.page) searchParams.set('page', String(params.page));
      if (params?.wedding_id) searchParams.set('wedding_id', String(params.wedding_id));
      const url = `/seatings${searchParams.toString() ? `?${searchParams.toString()}` : ''}`;
      const response = await api.get<ApiResponse<PaginatedData<SeatingTable>>>(url);
      return response.data.data;
    },
  });
}

export function useSeatingTable(uuid: string | null) {
  return useQuery({
    queryKey: ['seating-table', uuid],
    queryFn: async () => {
      const response = await api.get<ApiResponse<SeatingTable>>(`/seatings/${uuid}`);
      return response.data.data;
    },
    enabled: !!uuid,
  });
}

export function useCreateSeatingTable() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (data: SeatingTableFormData) => {
      const response = await api.post<ApiResponse<SeatingTable>>('/seatings', data);
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['seating-tables'] });
    },
  });
}

export function useUpdateSeatingTable() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async ({ uuid, data }: { uuid: string; data: Partial<SeatingTableFormData> }) => {
      const response = await api.put<ApiResponse<SeatingTable>>(`/seatings/${uuid}`, data);
      return response.data.data;
    },
    onSuccess: (_, variables) => {
      queryClient.invalidateQueries({ queryKey: ['seating-tables'] });
      queryClient.invalidateQueries({ queryKey: ['seating-table', variables.uuid] });
    },
  });
}

export function useDeleteSeatingTable() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (uuid: string) => {
      await api.delete(`/seatings/${uuid}`);
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['seating-tables'] });
    },
  });
}

export function useAssignGuest() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async ({ tableUuid, data }: { tableUuid: string; data: SeatAssignmentFormData }) => {
      const response = await api.post<ApiResponse<SeatingTable>>(`/seatings/${tableUuid}/assign`, data);
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['seating-tables'] });
    },
  });
}

export function useUnassignGuest() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async ({ tableUuid, assignmentUuid }: { tableUuid: string; assignmentUuid: string }) => {
      await api.delete(`/seatings/${tableUuid}/unassign/${assignmentUuid}`);
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['seating-tables'] });
    },
  });
}

export function useAutoGenerateSeating() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (wedding_id?: number) => {
      const response = await api.post<ApiResponse<{ assigned: number }>>(
        '/seatings/auto-generate',
        wedding_id ? { wedding_id } : {},
      );
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['seating-tables'] });
    },
  });
}

export function usePreviewSeating(params?: { wedding_id?: number }) {
  return useQuery({
    queryKey: ['seating-preview', params],
    queryFn: async () => {
      const searchParams = new URLSearchParams();
      if (params?.wedding_id) searchParams.set('wedding_id', String(params.wedding_id));
      const url = `/seatings/preview${searchParams.toString() ? `?${searchParams.toString()}` : ''}`;
      const response = await api.get<ApiResponse<SeatingPreview>>(url);
      return response.data.data;
    },
  });
}
