'use client';

import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '@/services/api';
import type { ApiResponse, PaginatedData, Timeline, TimelineFormData, ReorderItem } from '@/types';

export function useTimelines(params?: { wedding_id?: number; page?: number }) {
  return useQuery({
    queryKey: ['timelines', params],
    queryFn: async () => {
      const searchParams = new URLSearchParams();
      if (params?.page) searchParams.set('page', String(params.page));
      if (params?.wedding_id) searchParams.set('wedding_id', String(params.wedding_id));
      const url = `/timelines${searchParams.toString() ? `?${searchParams.toString()}` : ''}`;
      const response = await api.get<ApiResponse<PaginatedData<Timeline>>>(url);
      return response.data.data;
    },
  });
}

export function useTimeline(uuid: string | null) {
  return useQuery({
    queryKey: ['timeline', uuid],
    queryFn: async () => {
      const response = await api.get<ApiResponse<Timeline>>(`/timelines/${uuid}`);
      return response.data.data;
    },
    enabled: !!uuid,
  });
}

export function useCreateTimeline() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (data: TimelineFormData) => {
      const response = await api.post<ApiResponse<Timeline>>('/timelines', data);
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['timelines'] });
    },
  });
}

export function useUpdateTimeline() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async ({ uuid, data }: { uuid: string; data: Partial<TimelineFormData> }) => {
      const response = await api.put<ApiResponse<Timeline>>(`/timelines/${uuid}`, data);
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['timelines'] });
      queryClient.invalidateQueries({ queryKey: ['timeline'] });
    },
  });
}

export function useDeleteTimeline() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (uuid: string) => {
      const response = await api.delete<ApiResponse<void>>(`/timelines/${uuid}`);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['timelines'] });
    },
  });
}

export function useToggleTimelineComplete() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (uuid: string) => {
      const response = await api.patch<ApiResponse<Timeline>>(`/timelines/${uuid}/complete`);
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['timelines'] });
      queryClient.invalidateQueries({ queryKey: ['timeline'] });
    },
  });
}

export function useCompleteTimelineTask() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async ({ uuid, task_uuid }: { uuid: string; task_uuid: string }) => {
      const response = await api.post<ApiResponse<Timeline>>(`/timelines/${uuid}/complete-task`, { task_uuid });
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['timelines'] });
      queryClient.invalidateQueries({ queryKey: ['timeline'] });
    },
  });
}

export function useUncompleteTimelineTask() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async ({ uuid, task_uuid }: { uuid: string; task_uuid: string }) => {
      const response = await api.post<ApiResponse<Timeline>>(`/timelines/${uuid}/uncomplete-task`, { task_uuid });
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['timelines'] });
      queryClient.invalidateQueries({ queryKey: ['timeline'] });
    },
  });
}

export function useReorderTimelineTasks() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async ({ uuid, tasks }: { uuid: string; tasks: ReorderItem[] }) => {
      const response = await api.patch<ApiResponse<Timeline>>(`/timelines/${uuid}/reorder`, { tasks });
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['timelines'] });
      queryClient.invalidateQueries({ queryKey: ['timeline'] });
    },
  });
}

export function useGenerateTimelineAi() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async () => {
      const response = await api.post<ApiResponse<Timeline>>('/timelines/generate-ai');
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['timelines'] });
    },
  });
}

export function useSyncGoogleCalendar() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (uuid: string) => {
      const response = await api.post<ApiResponse<{ status: string; timeline_id: string; tasks_count: number }>>(`/timelines/${uuid}/sync-google-calendar`);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['timelines'] });
    },
  });
}
