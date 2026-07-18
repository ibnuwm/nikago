'use client';

import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '@/services/api';
import type { ApiResponse, PaginatedData, Notification, UnreadCount } from '@/types';

interface UseNotificationsParams {
  page?: number;
  per_page?: number;
  is_read?: boolean;
  type?: string;
}

export function useNotifications(params?: UseNotificationsParams) {
  return useQuery({
    queryKey: ['notifications', params],
    queryFn: async () => {
      const searchParams = new URLSearchParams();
      if (params?.page) searchParams.set('page', String(params.page));
      if (params?.per_page) searchParams.set('per_page', String(params.per_page));
      if (params?.is_read !== undefined) searchParams.set('is_read', String(params.is_read));
      if (params?.type) searchParams.set('type', params.type);
      const queryString = searchParams.toString();
      const url = `/notifications${queryString ? `?${queryString}` : ''}`;
      const response = await api.get<ApiResponse<PaginatedData<Notification>>>(url);
      return response.data.data;
    },
  });
}

export function useUnreadCount() {
  return useQuery({
    queryKey: ['notifications', 'unread'],
    queryFn: async () => {
      const response = await api.get<ApiResponse<UnreadCount>>('/notifications/unread');
      return response.data.data;
    },
    refetchInterval: 30000,
  });
}

export function useMarkAsRead() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (uuid: string) => {
      const response = await api.patch<ApiResponse<Notification>>(`/notifications/${uuid}/read`);
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['notifications'] });
    },
  });
}

export function useMarkAllAsRead() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async () => {
      const response = await api.patch<ApiResponse<{ updated: number }>>('/notifications/read-all');
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['notifications'] });
    },
  });
}

export function useDeleteNotification() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (uuid: string) => {
      await api.delete(`/notifications/${uuid}`);
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['notifications'] });
    },
  });
}
