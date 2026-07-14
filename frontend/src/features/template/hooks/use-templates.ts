import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '@/services/api';
import type { ApiResponse, PaginatedData, InvitationTemplate } from '@/types';

interface UseTemplatesParams {
  page?: number;
  per_page?: number;
  search?: string;
  category?: string;
  premium?: string;
  sort?: string;
  direction?: string;
}

export function useTemplates(params?: UseTemplatesParams) {
  return useQuery({
    queryKey: ['templates', params],
    queryFn: async () => {
      const searchParams = new URLSearchParams();
      if (params?.page) searchParams.set('page', String(params.page));
      if (params?.per_page) searchParams.set('per_page', String(params.per_page));
      if (params?.search) searchParams.set('search', params.search);
      if (params?.category) searchParams.set('category', params.category);
      if (params?.premium) searchParams.set('premium', params.premium);
      if (params?.sort) searchParams.set('sort', params.sort);
      if (params?.direction) searchParams.set('direction', params.direction);

      const queryString = searchParams.toString();
      const url = `/templates${queryString ? `?${queryString}` : ''}`;

      const response = await api.get<ApiResponse<PaginatedData<InvitationTemplate>>>(url);
      return response.data.data;
    },
  });
}

export function useTemplate(uuid: string | null) {
  return useQuery({
    queryKey: ['template', uuid],
    queryFn: async () => {
      const response = await api.get<ApiResponse<InvitationTemplate>>(`/templates/${uuid}`);
      return response.data.data;
    },
    enabled: !!uuid,
  });
}

export function useTemplateCategories() {
  return useQuery({
    queryKey: ['template-categories'],
    queryFn: async () => {
      const response = await api.get<ApiResponse<string[]>>('/templates/categories');
      return response.data.data;
    },
  });
}

export function usePremiumTemplates(params?: Omit<UseTemplatesParams, 'premium'>) {
  return useQuery({
    queryKey: ['premium-templates', params],
    queryFn: async () => {
      const searchParams = new URLSearchParams();
      if (params?.page) searchParams.set('page', String(params.page));
      if (params?.per_page) searchParams.set('per_page', String(params.per_page));
      if (params?.search) searchParams.set('search', params.search);
      if (params?.sort) searchParams.set('sort', params.sort);
      if (params?.direction) searchParams.set('direction', params.direction);

      const queryString = searchParams.toString();
      const url = `/templates/premium${queryString ? `?${queryString}` : ''}`;

      const response = await api.get<ApiResponse<PaginatedData<InvitationTemplate>>>(url);
      return response.data.data;
    },
  });
}

export function useFavoriteTemplate() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (uuid: string) => {
      await api.post(`/templates/${uuid}/favorite`);
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['templates'] });
      queryClient.invalidateQueries({ queryKey: ['premium-templates'] });
    },
  });
}

export function useUnfavoriteTemplate() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (uuid: string) => {
      await api.delete(`/templates/${uuid}/favorite`);
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['templates'] });
      queryClient.invalidateQueries({ queryKey: ['premium-templates'] });
    },
  });
}
