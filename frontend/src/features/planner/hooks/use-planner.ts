import { useQuery, useMutation } from '@tanstack/react-query';
import api from '@/services/api';
import type {
  ApiResponse,
  PlannerData,
  PlannerProgress,
  PlannerSummary,
  PlannerExportData,
  AiPlannerData,
} from '@/types';

export function usePlannerDashboard() {
  return useQuery({
    queryKey: ['planner'],
    queryFn: async () => {
      const response = await api.get<ApiResponse<PlannerData>>('/planner');
      return response.data.data;
    },
  });
}

export function usePlannerSummary() {
  return useQuery({
    queryKey: ['planner', 'summary'],
    queryFn: async () => {
      const response = await api.get<ApiResponse<PlannerSummary>>('/planner/summary');
      return response.data.data;
    },
  });
}

export function usePlannerProgress() {
  return useQuery({
    queryKey: ['planner', 'progress'],
    queryFn: async () => {
      const response = await api.get<ApiResponse<PlannerProgress>>('/planner/progress');
      return response.data.data;
    },
  });
}

export function useGeneratePlannerAi() {
  return useMutation({
    mutationFn: async () => {
      const response = await api.post<{ success: boolean; data: AiPlannerData }>('/planner/generate-ai');
      return response.data.data;
    },
  });
}

export function useExportPlanner() {
  return useQuery({
    queryKey: ['planner', 'export'],
    queryFn: async () => {
      const response = await api.get<ApiResponse<PlannerExportData>>('/planner/export');
      return response.data.data;
    },
  });
}
