'use client';

import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '@/services/api';
import type {
  ApiResponse,
  PaginatedData,
  Review,
  ReviewFormData,
  ReviewReport,
} from '@/types';

export function useReviews(params?: { per_page?: number }) {
  return useQuery({
    queryKey: ['reviews', params],
    queryFn: async () => {
      const searchParams = new URLSearchParams();
      if (params?.per_page) searchParams.set('per_page', String(params.per_page));
      const qs = searchParams.toString();
      const url = `/reviews${qs ? `?${qs}` : ''}`;
      const response = await api.get<ApiResponse<PaginatedData<Review>>>(url);
      return response.data.data;
    },
  });
}

export function useReview(uuid: string | null) {
  return useQuery({
    queryKey: ['review', uuid],
    queryFn: async () => {
      const response = await api.get<ApiResponse<Review>>(`/reviews/${uuid}`);
      return response.data.data;
    },
    enabled: !!uuid,
  });
}

export function useVendorReviews(vendorUuid: string | null) {
  return useQuery({
    queryKey: ['vendor-reviews', vendorUuid],
    queryFn: async () => {
      const response = await api.get<ApiResponse<PaginatedData<Review>>>(`/vendors/${vendorUuid}/reviews`);
      return response.data.data;
    },
    enabled: !!vendorUuid,
  });
}

export function useCreateReview() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (data: ReviewFormData) => {
      const response = await api.post<ApiResponse<Review>>('/reviews', data);
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['reviews'] });
      queryClient.invalidateQueries({ queryKey: ['vendor-reviews'] });
    },
  });
}

export function useUpdateReview() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async ({ uuid, data }: { uuid: string; data: Partial<ReviewFormData> }) => {
      const response = await api.put<ApiResponse<Review>>(`/reviews/${uuid}`, data);
      return response.data.data;
    },
    onSuccess: (_, variables) => {
      queryClient.invalidateQueries({ queryKey: ['reviews'] });
      queryClient.invalidateQueries({ queryKey: ['review', variables.uuid] });
      queryClient.invalidateQueries({ queryKey: ['vendor-reviews'] });
    },
  });
}

export function useDeleteReview() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (uuid: string) => {
      await api.delete(`/reviews/${uuid}`);
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['reviews'] });
      queryClient.invalidateQueries({ queryKey: ['vendor-reviews'] });
    },
  });
}

export function useReplyToReview() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async ({ uuid, reply }: { uuid: string; reply: string }) => {
      const response = await api.post<ApiResponse<Review>>(`/reviews/${uuid}/reply`, { reply });
      return response.data.data;
    },
    onSuccess: (_, variables) => {
      queryClient.invalidateQueries({ queryKey: ['review', variables.uuid] });
      queryClient.invalidateQueries({ queryKey: ['vendor-reviews'] });
    },
  });
}

export function useReportReview() {
  return useMutation({
    mutationFn: async ({ uuid, reason }: { uuid: string; reason: string }) => {
      const response = await api.post<ApiResponse<ReviewReport>>(`/reviews/${uuid}/report`, { reason });
      return response.data.data;
    },
  });
}
