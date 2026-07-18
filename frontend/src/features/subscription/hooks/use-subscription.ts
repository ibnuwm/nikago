import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '@/services/api';
import type { ApiResponse, Subscription, SubscriptionPlan, SubscriptionHistory } from '@/types';

export function usePlans() {
  return useQuery({
    queryKey: ['subscription', 'plans'],
    queryFn: async () => {
      const response = await api.get<ApiResponse<SubscriptionPlan[]>>('/subscriptions/plans');
      return response.data.data;
    },
  });
}

export function useCurrentSubscription() {
  return useQuery({
    queryKey: ['subscription', 'current'],
    queryFn: async () => {
      const response = await api.get<ApiResponse<Subscription>>('/subscriptions/current');
      return response.data.data;
    },
  });
}

export function useSubscribe() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (data: { plan_code: string; billing_period: 'monthly' | 'yearly' }) => {
      const response = await api.post<ApiResponse<Subscription>>('/subscriptions/subscribe', data);
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['subscription'] });
    },
  });
}

export function useUpgradeSubscription() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (data: { plan_code: string; billing_period?: 'monthly' | 'yearly' }) => {
      const response = await api.post<ApiResponse<Subscription>>('/subscriptions/upgrade', data);
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['subscription'] });
    },
  });
}

export function useDowngradeSubscription() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (data: { plan_code: string }) => {
      const response = await api.post<ApiResponse<Subscription>>('/subscriptions/downgrade', data);
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['subscription'] });
    },
  });
}

export function useCancelSubscription() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (data?: { reason?: string }) => {
      const response = await api.post<ApiResponse<Subscription>>('/subscriptions/cancel', data ?? {});
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['subscription'] });
    },
  });
}

export function useSubscriptionHistory() {
  return useQuery({
    queryKey: ['subscription', 'history'],
    queryFn: async () => {
      const response = await api.get<ApiResponse<SubscriptionHistory[]>>('/subscriptions/history');
      return response.data.data;
    },
  });
}

export function useFeatures() {
  return useQuery({
    queryKey: ['subscription', 'features'],
    queryFn: async () => {
      const response = await api.get<ApiResponse<{ code: string; name: string; description: string }[]>>('/subscriptions/features');
      return response.data.data;
    },
  });
}
