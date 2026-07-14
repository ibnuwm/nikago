import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '@/services/api';
import type { ApiResponse, PaginatedData, Invitation, InvitationFormData } from '@/types';

interface UseInvitationsParams {
  page?: number;
  per_page?: number;
  search?: string;
  status?: string;
  sort?: string;
  direction?: string;
}

export function useInvitations(params?: UseInvitationsParams) {
  return useQuery({
    queryKey: ['invitations', params],
    queryFn: async () => {
      const searchParams = new URLSearchParams();
      if (params?.page) searchParams.set('page', String(params.page));
      if (params?.per_page) searchParams.set('per_page', String(params.per_page));
      if (params?.search) searchParams.set('search', params.search);
      if (params?.status) searchParams.set('status', params.status);
      if (params?.sort) searchParams.set('sort', params.sort);
      if (params?.direction) searchParams.set('direction', params.direction);

      const queryString = searchParams.toString();
      const url = `/invitations${queryString ? `?${queryString}` : ''}`;

      const response = await api.get<ApiResponse<PaginatedData<Invitation>>>(url);
      return response.data.data;
    },
  });
}

export function useInvitation(uuid: string | null) {
  return useQuery({
    queryKey: ['invitation', uuid],
    queryFn: async () => {
      const response = await api.get<ApiResponse<Invitation>>(`/invitations/${uuid}`);
      return response.data.data;
    },
    enabled: !!uuid,
  });
}

export function useCreateInvitation() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (data: InvitationFormData) => {
      const response = await api.post<ApiResponse<Invitation>>('/invitations', data);
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['invitations'] });
    },
  });
}

export function useUpdateInvitation() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async ({ uuid, data }: { uuid: string; data: InvitationFormData }) => {
      const response = await api.put<ApiResponse<Invitation>>(`/invitations/${uuid}`, data);
      return response.data.data;
    },
    onSuccess: (_, variables) => {
      queryClient.invalidateQueries({ queryKey: ['invitations'] });
      queryClient.invalidateQueries({ queryKey: ['invitation', variables.uuid] });
    },
  });
}

export function useDeleteInvitation() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (uuid: string) => {
      await api.delete(`/invitations/${uuid}`);
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['invitations'] });
    },
  });
}

export function usePublishInvitation() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (uuid: string) => {
      const response = await api.patch<ApiResponse<Invitation>>(`/invitations/${uuid}/publish`);
      return response.data.data;
    },
    onSuccess: (_, uuid) => {
      queryClient.invalidateQueries({ queryKey: ['invitations'] });
      queryClient.invalidateQueries({ queryKey: ['invitation', uuid] });
    },
  });
}

export function useDraftInvitation() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (uuid: string) => {
      const response = await api.patch<ApiResponse<Invitation>>(`/invitations/${uuid}/draft`);
      return response.data.data;
    },
    onSuccess: (_, uuid) => {
      queryClient.invalidateQueries({ queryKey: ['invitations'] });
      queryClient.invalidateQueries({ queryKey: ['invitation', uuid] });
    },
  });
}

export function useDuplicateInvitation() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (uuid: string) => {
      const response = await api.post<ApiResponse<Invitation>>(`/invitations/${uuid}/duplicate`);
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['invitations'] });
    },
  });
}

export function usePreviewInvitation(uuid: string | null) {
  return useQuery({
    queryKey: ['invitation-preview', uuid],
    queryFn: async () => {
      const response = await api.get<ApiResponse<Invitation>>(`/invitations/${uuid}/preview`);
      return response.data.data;
    },
    enabled: !!uuid,
  });
}
