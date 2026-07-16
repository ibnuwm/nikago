'use client';

import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '@/services/api';
import type {
  ApiResponse,
  PaginatedData,
  Booking,
  BookingFormData,
  CalendarEvent,
  BookingHistory,
} from '@/types';

export function useBookings(params?: { status?: string }) {
  return useQuery({
    queryKey: ['bookings', params],
    queryFn: async () => {
      const searchParams = new URLSearchParams();
      if (params?.status) searchParams.set('status', params.status);
      const qs = searchParams.toString();
      const url = `/bookings${qs ? `?${qs}` : ''}`;
      const response = await api.get<ApiResponse<PaginatedData<Booking>>>(url);
      return response.data.data;
    },
  });
}

export function useBooking(uuid: string | null) {
  return useQuery({
    queryKey: ['booking', uuid],
    queryFn: async () => {
      const response = await api.get<ApiResponse<Booking>>(`/bookings/${uuid}`);
      return response.data.data;
    },
    enabled: !!uuid,
  });
}

export function useCreateBooking() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (data: BookingFormData) => {
      const response = await api.post<ApiResponse<Booking>>('/bookings', data);
      return response.data.data;
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['bookings'] });
    },
  });
}

export function useUpdateBooking() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async ({ uuid, data }: { uuid: string; data: Partial<BookingFormData> }) => {
      const response = await api.put<ApiResponse<Booking>>(`/bookings/${uuid}`, data);
      return response.data.data;
    },
    onSuccess: (_, variables) => {
      queryClient.invalidateQueries({ queryKey: ['bookings'] });
      queryClient.invalidateQueries({ queryKey: ['booking', variables.uuid] });
    },
  });
}

export function useConfirmBooking() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (uuid: string) => {
      const response = await api.patch<ApiResponse<Booking>>(`/bookings/${uuid}/confirm`);
      return response.data.data;
    },
    onSuccess: (_, uuid) => {
      queryClient.invalidateQueries({ queryKey: ['bookings'] });
      queryClient.invalidateQueries({ queryKey: ['booking', uuid] });
    },
  });
}

export function useCancelBooking() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (uuid: string) => {
      const response = await api.patch<ApiResponse<Booking>>(`/bookings/${uuid}/cancel`);
      return response.data.data;
    },
    onSuccess: (_, uuid) => {
      queryClient.invalidateQueries({ queryKey: ['bookings'] });
      queryClient.invalidateQueries({ queryKey: ['booking', uuid] });
    },
  });
}

export function useCompleteBooking() {
  const queryClient = useQueryClient();
  return useMutation({
    mutationFn: async (uuid: string) => {
      const response = await api.patch<ApiResponse<Booking>>(`/bookings/${uuid}/complete`);
      return response.data.data;
    },
    onSuccess: (_, uuid) => {
      queryClient.invalidateQueries({ queryKey: ['bookings'] });
      queryClient.invalidateQueries({ queryKey: ['booking', uuid] });
    },
  });
}

export function useBookingHistory(uuid: string | null) {
  return useQuery({
    queryKey: ['booking-history', uuid],
    queryFn: async () => {
      const response = await api.get<ApiResponse<BookingHistory[]>>(`/bookings/history/${uuid}`);
      return response.data.data;
    },
    enabled: !!uuid,
  });
}

export function useBookingCalendar(params?: { vendor_uuid?: string; year?: number; month?: number }) {
  return useQuery({
    queryKey: ['booking-calendar', params],
    queryFn: async () => {
      const searchParams = new URLSearchParams();
      if (params?.vendor_uuid) searchParams.set('vendor_uuid', params.vendor_uuid);
      if (params?.year) searchParams.set('year', String(params.year));
      if (params?.month) searchParams.set('month', String(params.month));
      const qs = searchParams.toString();
      const url = `/bookings/calendar${qs ? `?${qs}` : ''}`;
      const response = await api.get<ApiResponse<CalendarEvent[]>>(url);
      return response.data.data;
    },
  });
}
