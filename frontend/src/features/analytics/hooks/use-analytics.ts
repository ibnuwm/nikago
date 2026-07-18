import { useQuery } from '@tanstack/react-query';
import api from '@/services/api';
import type {
  ApiResponse,
  AnalyticsDashboard,
  AnalyticsInvitation,
  AnalyticsRsvp,
  AnalyticsGuest,
  AnalyticsVendor,
  AnalyticsSubscription,
  AnalyticsRevenue,
  AnalyticsTraffic,
  AnalyticsAi,
} from '@/types';

interface AnalyticsFilters {
  start_date?: string;
  end_date?: string;
}

function buildParams(filters?: AnalyticsFilters): URLSearchParams {
  const params = new URLSearchParams();
  if (filters?.start_date) params.set('start_date', filters.start_date);
  if (filters?.end_date) params.set('end_date', filters.end_date);
  return params;
}

export function useDashboardAnalytics(filters?: AnalyticsFilters) {
  return useQuery({
    queryKey: ['analytics', 'dashboard', filters],
    queryFn: async () => {
      const response = await api.get<ApiResponse<AnalyticsDashboard>>('/analytics/dashboard', {
        params: buildParams(filters),
      });
      return response.data.data;
    },
  });
}

export function useInvitationAnalytics(filters?: AnalyticsFilters) {
  return useQuery({
    queryKey: ['analytics', 'invitations', filters],
    queryFn: async () => {
      const response = await api.get<ApiResponse<AnalyticsInvitation>>('/analytics/invitations', {
        params: buildParams(filters),
      });
      return response.data.data;
    },
  });
}

export function useRsvpAnalytics(filters?: AnalyticsFilters) {
  return useQuery({
    queryKey: ['analytics', 'rsvp', filters],
    queryFn: async () => {
      const response = await api.get<ApiResponse<AnalyticsRsvp>>('/analytics/rsvp', {
        params: buildParams(filters),
      });
      return response.data.data;
    },
  });
}

export function useGuestAnalytics(filters?: AnalyticsFilters) {
  return useQuery({
    queryKey: ['analytics', 'guests', filters],
    queryFn: async () => {
      const response = await api.get<ApiResponse<AnalyticsGuest>>('/analytics/guests', {
        params: buildParams(filters),
      });
      return response.data.data;
    },
  });
}

export function useVendorAnalytics(filters?: AnalyticsFilters) {
  return useQuery({
    queryKey: ['analytics', 'vendors', filters],
    queryFn: async () => {
      const response = await api.get<ApiResponse<AnalyticsVendor>>('/analytics/vendors', {
        params: buildParams(filters),
      });
      return response.data.data;
    },
  });
}

export function useSubscriptionAnalytics(filters?: AnalyticsFilters) {
  return useQuery({
    queryKey: ['analytics', 'subscriptions', filters],
    queryFn: async () => {
      const response = await api.get<ApiResponse<AnalyticsSubscription>>('/analytics/subscriptions', {
        params: buildParams(filters),
      });
      return response.data.data;
    },
  });
}

export function useRevenueAnalytics(filters?: AnalyticsFilters) {
  return useQuery({
    queryKey: ['analytics', 'revenue', filters],
    queryFn: async () => {
      const response = await api.get<ApiResponse<AnalyticsRevenue>>('/analytics/revenue', {
        params: buildParams(filters),
      });
      return response.data.data;
    },
  });
}

export function useTrafficAnalytics(filters?: AnalyticsFilters) {
  return useQuery({
    queryKey: ['analytics', 'traffic', filters],
    queryFn: async () => {
      const response = await api.get<ApiResponse<AnalyticsTraffic>>('/analytics/traffic', {
        params: buildParams(filters),
      });
      return response.data.data;
    },
  });
}

export function useAiAnalytics(filters?: AnalyticsFilters) {
  return useQuery({
    queryKey: ['analytics', 'ai', filters],
    queryFn: async () => {
      const response = await api.get<ApiResponse<AnalyticsAi>>('/analytics/ai', {
        params: buildParams(filters),
      });
      return response.data.data;
    },
  });
}


