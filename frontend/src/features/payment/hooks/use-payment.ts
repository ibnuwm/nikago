import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '@/services/api';
import type { ApiResponse, PaginatedData, Payment } from '@/types';

interface UsePaymentsParams {
  page?: number;
  per_page?: number;
  status?: string;
}

export function usePayments(params?: UsePaymentsParams) {
  return useQuery({
    queryKey: ['payments', params],
    queryFn: async () => {
      const searchParams = new URLSearchParams();
      if (params?.page) searchParams.set('page', String(params.page));
      if (params?.per_page) searchParams.set('per_page', String(params.per_page));
      if (params?.status) searchParams.set('status', params.status);
      const queryString = searchParams.toString();
      const url = `/payments${queryString ? `?${queryString}` : ''}`;
      const response = await api.get<ApiResponse<PaginatedData<Payment>>>(url);
      return response.data.data;
    },
  });
}

export function usePayment(uuid: string | null) {
  return useQuery({
    queryKey: ['payment', uuid],
    queryFn: async () => {
      const response = await api.get<ApiResponse<Payment>>(`/payments/${uuid}`);
      return response.data.data;
    },
    enabled: !!uuid,
  });
}

export function usePayPayment() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async ({ uuid, data }: { uuid: string; data?: Record<string, unknown> }) => {
      const response = await api.post<ApiResponse<Payment>>(`/payments/${uuid}/pay`, data ?? {});
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['payments'] });
    },
  });
}

export function useRefundPayment() {
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (uuid: string) => {
      const response = await api.post<ApiResponse<Payment>>(`/payments/${uuid}/refund`);
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['payments'] });
    },
  });
}
