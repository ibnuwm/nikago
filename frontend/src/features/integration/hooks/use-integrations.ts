'use client';

import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '@/services/api';
import type {
  ApiResponse,
  UserIntegration,
  IntegrationProvider,
  WebhookItem,
  IntegrationTestResult,
} from '@/types';

export function useIntegrations() {
  return useQuery({
    queryKey: ['integrations'],
    queryFn: async () => {
      const response = await api.get<ApiResponse<UserIntegration[]>>('/api/integrations');
      return response.data.data;
    },
  });
}

export function useProviders() {
  return useQuery({
    queryKey: ['integrations', 'providers'],
    queryFn: async () => {
      const response = await api.get<ApiResponse<IntegrationProvider[]>>('/api/integrations/providers');
      return response.data.data;
    },
  });
}

export function useConnectGoogle() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (data: { access_token: string; refresh_token?: string }) => {
      const response = await api.post<ApiResponse<{ message: string }>>('/api/integrations/google/connect', data);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['integrations'] });
    },
  });
}

export function useDisconnectGoogle() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async () => {
      await api.delete('/api/integrations/google/disconnect');
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['integrations'] });
    },
  });
}

export function useConnectCalendar() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (data: { access_token: string }) => {
      const response = await api.post<ApiResponse<{ message: string }>>('/api/integrations/calendar/connect', data);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['integrations'] });
    },
  });
}

export function useDisconnectCalendar() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async () => {
      await api.delete('/api/integrations/calendar/disconnect');
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['integrations'] });
    },
  });
}

export function useConnectWhatsapp() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (data: { api_key: string }) => {
      const response = await api.post<ApiResponse<{ message: string }>>('/api/integrations/whatsapp/connect', data);
      return response.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['integrations'] });
    },
  });
}

export function useDisconnectWhatsapp() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async () => {
      await api.delete('/api/integrations/whatsapp/disconnect');
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['integrations'] });
    },
  });
}

export function useWebhooks() {
  return useQuery({
    queryKey: ['integrations', 'webhooks'],
    queryFn: async () => {
      const response = await api.get<ApiResponse<WebhookItem[]>>('/api/integrations/webhooks');
      return response.data.data;
    },
  });
}

export function useCreateWebhook() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (data: { name: string; url: string; events?: string[] }) => {
      const response = await api.post<ApiResponse<WebhookItem>>('/api/integrations/webhooks', data);
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['integrations', 'webhooks'] });
    },
  });
}

export function useDeleteWebhook() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (uuid: string) => {
      await api.delete(`/api/integrations/webhooks/${uuid}`);
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['integrations', 'webhooks'] });
    },
  });
}

export function useTestIntegration() {
  return useMutation({
    mutationFn: async (provider: string) => {
      const response = await api.post<ApiResponse<IntegrationTestResult>>('/api/integrations/test', { provider });
      return response.data.data;
    },
  });
}
