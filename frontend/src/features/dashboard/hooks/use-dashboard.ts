import { useQuery } from '@tanstack/react-query';
import api from '@/services/api';
import type { ApiResponse, DashboardData } from '@/types';

export function useDashboard() {
  return useQuery({
    queryKey: ['dashboard'],
    queryFn: async () => {
      const response = await api.get<ApiResponse<DashboardData>>('/dashboard');
      return response.data.data;
    },
  });
}
