import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '@/services/api';
import type { ApiResponse, AuthResponse, LoginData, RegisterData, User } from '@/types';
import { useAuthStore } from '@/stores/auth-store';

export function useUser() {
  const setUser = useAuthStore((s) => s.setUser);
  const token = useAuthStore((s) => s.token);

  return useQuery({
    queryKey: ['user'],
    queryFn: async () => {
      const response = await api.get<ApiResponse<{ user: User }>>('/auth/me');
      setUser(response.data.data.user);
      return response.data.data.user;
    },
    enabled: !!token,
    retry: false,
  });
}

export function useLogin() {
  const { setToken, setUser } = useAuthStore();
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (data: LoginData) => {
      const response = await api.post<ApiResponse<AuthResponse>>('/auth/login', data);
      return response.data;
    },
    onSuccess: (data) => {
      setToken(data.data.token);
      setUser(data.data.user);
      queryClient.setQueryData(['user'], data.data.user);
    },
  });
}

export function useRegister() {
  const { setToken, setUser } = useAuthStore();
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (data: RegisterData) => {
      const response = await api.post<ApiResponse<AuthResponse>>('/auth/register', data);
      return response.data;
    },
    onSuccess: (data) => {
      setToken(data.data.token);
      setUser(data.data.user);
      queryClient.setQueryData(['user'], data.data.user);
    },
  });
}

export function useLogout() {
  const { logout } = useAuthStore();
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async () => {
      await api.post('/auth/logout');
    },
    onSuccess: () => {
      logout();
      queryClient.clear();
    },
  });
}

export function useForgotPassword() {
  return useMutation({
    mutationFn: async (data: { email: string }) => {
      const response = await api.post<ApiResponse<{ message: string }>>('/auth/forgot-password', data);
      return response.data;
    },
  });
}

export function useResetPassword() {
  return useMutation({
    mutationFn: async (data: { token: string; email: string; password: string; password_confirmation: string }) => {
      const response = await api.post<ApiResponse<{ message: string }>>('/auth/reset-password', data);
      return response.data;
    },
  });
}
