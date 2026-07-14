import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '@/services/api';
import type { ApiResponse, User } from '@/types';
import { useAuthStore } from '@/stores/auth-store';

export function useUser() {
  const setUser = useAuthStore((s) => s.setUser);

  return useQuery({
    queryKey: ['user'],
    queryFn: async () => {
      const response = await api.get<ApiResponse<User>>('/user');
      setUser(response.data.data);
      return response.data.data;
    },
    enabled: !!useAuthStore.getState().token,
    retry: false,
  });
}

export function useLogin() {
  const { setToken, setUser } = useAuthStore();
  const queryClient = useQueryClient();

  return useMutation({
    mutationFn: async (data: { email: string; password: string }) => {
      const response = await api.post<ApiResponse<{ user: User; token: string }>>('/login', data);
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
    mutationFn: async (data: { name: string; email: string; password: string; password_confirmation: string }) => {
      const response = await api.post<ApiResponse<{ user: User; token: string }>>('/register', data);
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
      await api.post('/logout');
    },
    onSuccess: () => {
      logout();
      queryClient.clear();
    },
  });
}
