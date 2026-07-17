'use client';

import { useQuery } from '@tanstack/react-query';
import api from '@/services/api';
import type { ApiResponse, BlogCategoryItem, BlogPost, BlogTagItem, PaginatedData } from '@/types';

interface BlogPostsParams {
  search?: string;
  category?: string;
  tag?: string;
  page?: number;
  per_page?: number;
}

export function useBlogPosts(params: BlogPostsParams = {}) {
  return useQuery({
    queryKey: ['cms', 'blog', 'posts', params],
    queryFn: async () => {
      const response = await api.get<ApiResponse<BlogPost[]> & { meta: PaginatedData<BlogPost>['meta'] }>('/cms/blog/posts', { params });
      return response.data;
    },
  });
}

export function useBlogPost(slug: string) {
  return useQuery({
    queryKey: ['cms', 'blog', 'post', slug],
    queryFn: async () => {
      const response = await api.get<ApiResponse<BlogPost>>(`/cms/blog/posts/${slug}`);
      return response.data.data;
    },
    enabled: !!slug,
  });
}

export function useBlogCategories() {
  return useQuery({
    queryKey: ['cms', 'blog', 'categories'],
    queryFn: async () => {
      const response = await api.get<ApiResponse<BlogCategoryItem[]>>('/cms/blog/categories');
      return response.data.data;
    },
  });
}

export function useBlogTags() {
  return useQuery({
    queryKey: ['cms', 'blog', 'tags'],
    queryFn: async () => {
      const response = await api.get<ApiResponse<BlogTagItem[]>>('/cms/blog/tags');
      return response.data.data;
    },
  });
}
