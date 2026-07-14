import { useQuery } from '@tanstack/react-query';
import api from '@/services/api';
import type { ApiResponse, CmsFaq } from '@/types';

export function useFaqs() {
  return useQuery({
    queryKey: ['cms', 'faqs'],
    queryFn: async () => {
      const response = await api.get<ApiResponse<CmsFaq[]>>('/cms/faqs');
      return response.data.data;
    },
  });
}
