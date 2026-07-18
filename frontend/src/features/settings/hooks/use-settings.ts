'use client';

import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '@/services/api';
import type {
  ApiResponse,
  SettingsProfile,
  SettingsAccount,
  SettingsPreferences,
  SettingsNotificationPreferences,
  ApiKeyItem,
  CreateApiKeyResponse,
} from '@/types';

export function useProfile() {
  return useQuery({
    queryKey: ['settings', 'profile'],
    queryFn: async () => {
      const response = await api.get<ApiResponse<SettingsProfile>>('/api/settings/profile');
      return response.data.data;
    },
  });
}

export function useUpdateProfile() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (data: { name?: string; email?: string; phone?: string; avatar?: string }) => {
      const response = await api.put<ApiResponse<SettingsProfile>>('/api/settings/profile', data);
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['settings', 'profile'] });
      queryClient.invalidateQueries({ queryKey: ['user'] });
    },
  });
}

export function useAccount() {
  return useQuery({
    queryKey: ['settings', 'account'],
    queryFn: async () => {
      const response = await api.get<ApiResponse<SettingsAccount>>('/api/settings/account');
      return response.data.data;
    },
  });
}

export function useUpdateAccount() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (data: { timezone?: string; language?: string }) => {
      const response = await api.put<ApiResponse<SettingsAccount>>('/api/settings/account', data);
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['settings', 'account'] });
    },
  });
}

export function useUpdatePassword() {
  return useMutation({
    mutationFn: async (data: { current_password: string; password: string; password_confirmation: string }) => {
      const response = await api.put<ApiResponse<{ message: string }>>('/api/settings/password', data);
      return response.data.data;
    },
  });
}

export function usePreferences() {
  return useQuery({
    queryKey: ['settings', 'preferences'],
    queryFn: async () => {
      const response = await api.get<ApiResponse<SettingsPreferences>>('/api/settings/preferences');
      return response.data.data;
    },
  });
}

export function useUpdatePreferences() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (data: { theme?: string; language?: string; timezone?: string }) => {
      const response = await api.put<ApiResponse<SettingsPreferences>>('/api/settings/preferences', data);
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['settings', 'preferences'] });
    },
  });
}

export function useNotificationPreferences() {
  return useQuery({
    queryKey: ['settings', 'notifications'],
    queryFn: async () => {
      const response = await api.get<ApiResponse<SettingsNotificationPreferences>>('/api/settings/notifications');
      return response.data.data;
    },
  });
}

export function useUpdateNotificationPreferences() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (data: { in_app?: boolean; email?: boolean; whatsapp?: boolean }) => {
      const response = await api.put<ApiResponse<SettingsNotificationPreferences>>('/api/settings/notifications', data);
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['settings', 'notifications'] });
    },
  });
}

export function useApiKeys() {
  return useQuery({
    queryKey: ['settings', 'api-keys'],
    queryFn: async () => {
      const response = await api.get<ApiResponse<ApiKeyItem[]>>('/api/settings/api-keys');
      return response.data.data;
    },
  });
}

export function useCreateApiKey() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (data: { name: string; expires_at?: string }) => {
      const response = await api.post<ApiResponse<CreateApiKeyResponse>>('/api/settings/api-keys', data);
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['settings', 'api-keys'] });
    },
  });
}

export function useDeleteApiKey() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (uuid: string) => {
      await api.delete(`/api/settings/api-keys/${uuid}`);
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['settings', 'api-keys'] });
    },
  });
}
