import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '@/services/api';
import type {
  ApiResponse,
  PaginatedData,
  Checklist,
  ChecklistFormData,
  ReorderItem,
} from '@/types';

export function useChecklists(params?: { wedding_id?: number; page?: number }) {
  return useQuery({
    queryKey: ['checklists', params],
    queryFn: async () => {
      const searchParams = new URLSearchParams();
      if (params?.page) searchParams.set('page', String(params.page));
      if (params?.wedding_id) searchParams.set('wedding_id', String(params.wedding_id));
      const url = `/checklists${searchParams.toString() ? `?${searchParams.toString()}` : ''}`;
      const response = await api.get<ApiResponse<PaginatedData<Checklist>>>(url);
      return response.data.data;
    },
  });
}

export function useChecklist(uuid: string | null) {
  return useQuery({
    queryKey: ['checklist', uuid],
    queryFn: async () => {
      const response = await api.get<ApiResponse<Checklist>>(`/checklists/${uuid}`);
      return response.data.data;
    },
    enabled: !!uuid,
  });
}

export function useCreateChecklist() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (data: ChecklistFormData) => {
      const response = await api.post<ApiResponse<Checklist>>('/checklists', data);
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['checklists'] });
    },
  });
}

export function useUpdateChecklist() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async ({ uuid, data }: { uuid: string; data: Partial<ChecklistFormData> }) => {
      const response = await api.put<ApiResponse<Checklist>>(`/checklists/${uuid}`, data);
      return response.data.data;
    },
    onSuccess: (_, variables) => {
      queryClient.invalidateQueries({ queryKey: ['checklists'] });
      queryClient.invalidateQueries({ queryKey: ['checklist', variables.uuid] });
    },
  });
}

export function useDeleteChecklist() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (uuid: string) => {
      await api.delete(`/checklists/${uuid}`);
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['checklists'] });
    },
  });
}

export function useCompleteChecklistItem() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async ({ checklistUuid, itemUuid }: { checklistUuid: string; itemUuid: string }) => {
      const response = await api.post<ApiResponse<Checklist>>(`/checklists/${checklistUuid}/complete`, {
        item_uuid: itemUuid,
      });
      return response.data.data;
    },
    onSuccess: (_, variables) => {
      queryClient.invalidateQueries({ queryKey: ['checklist', variables.checklistUuid] });
      queryClient.invalidateQueries({ queryKey: ['checklists'] });
    },
  });
}

export function useUncompleteChecklistItem() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async ({ checklistUuid, itemUuid }: { checklistUuid: string; itemUuid: string }) => {
      const response = await api.post<ApiResponse<Checklist>>(`/checklists/${checklistUuid}/uncomplete`, {
        item_uuid: itemUuid,
      });
      return response.data.data;
    },
    onSuccess: (_, variables) => {
      queryClient.invalidateQueries({ queryKey: ['checklist', variables.checklistUuid] });
      queryClient.invalidateQueries({ queryKey: ['checklists'] });
    },
  });
}

export function useReorderChecklistItems() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async ({ uuid, items }: { uuid: string; items: ReorderItem[] }) => {
      const response = await api.patch<ApiResponse<Checklist>>(`/checklists/${uuid}/reorder`, { items });
      return response.data.data;
    },
    onSuccess: (_, variables) => {
      queryClient.invalidateQueries({ queryKey: ['checklist', variables.uuid] });
    },
  });
}

export function useDuplicateChecklist() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (uuid: string) => {
      const response = await api.post<ApiResponse<Checklist>>(`/checklists/${uuid}/duplicate`);
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['checklists'] });
    },
  });
}

export function useGenerateChecklistAi() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async () => {
      const response = await api.post<{ success: boolean; data: Checklist }>('/checklists/generate-ai');
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['checklists'] });
    },
  });
}
