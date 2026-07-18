'use client';

import { useQuery, useMutation } from '@tanstack/react-query';
import api from '@/services/api';
import type { ApiResponse, PaginatedData, AiMessage, AiChatResponse, AiGenerateResponse, AiHistoryItem, AiModel, AiUsage } from '@/types';

export function useAiChat() {
  return useMutation({
    mutationFn: async ({ messages, model, temperature }: { messages: AiMessage[]; model?: string; temperature?: number }) => {
      const response = await api.post<ApiResponse<AiChatResponse>>('/ai/chat', { messages, model, temperature });
      return response.data.data;
    },
  });
}

export function useAiGenerate() {
  return useMutation({
    mutationFn: async ({ feature, prompt, model }: { feature: string; prompt: string; model?: string }) => {
      const response = await api.post<ApiResponse<AiGenerateResponse>>(`/ai/${feature}`, { prompt, model });
      return response.data.data;
    },
  });
}

interface UseAiHistoryParams {
  page?: number;
  per_page?: number;
  feature?: string;
}

export function useAiHistory(params?: UseAiHistoryParams) {
  return useQuery({
    queryKey: ['ai-history', params],
    queryFn: async () => {
      const searchParams = new URLSearchParams();
      if (params?.page) searchParams.set('page', String(params.page));
      if (params?.per_page) searchParams.set('per_page', String(params.per_page));
      if (params?.feature) searchParams.set('feature', params.feature);
      const queryString = searchParams.toString();
      const url = `/ai/history${queryString ? `?${queryString}` : ''}`;
      const response = await api.get<ApiResponse<PaginatedData<AiHistoryItem>>>(url);
      return response.data.data;
    },
  });
}

export function useAiModels() {
  return useQuery({
    queryKey: ['ai-models'],
    queryFn: async () => {
      const response = await api.get<ApiResponse<AiModel[]>>('/ai/models');
      return response.data.data;
    },
    staleTime: 5 * 60 * 1000,
  });
}

export function useAiUsage() {
  return useQuery({
    queryKey: ['ai-usage'],
    queryFn: async () => {
      const response = await api.get<ApiResponse<AiUsage>>('/ai/usage');
      return response.data.data;
    },
  });
}
