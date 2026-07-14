export interface ApiResponse<T> {
  success: boolean;
  data: T;
  message?: string;
}

export interface ApiError {
  success: boolean;
  error: {
    code: string;
    message: string;
    errors?: Record<string, string[]>;
  };
}

export interface PaginatedData<T> {
  data: T[];
  meta: {
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
  };
}

export interface User {
  id: string;
  tenant_id: string | null;
  name: string;
  email: string;
  avatar?: string;
  phone?: string;
  email_verified_at: string | null;
  created_at: string;
  updated_at: string;
}

export interface Tenant {
  id: string;
  name: string;
  slug: string;
  domain: string | null;
  is_active: boolean;
  settings: Record<string, unknown>;
  created_at: string;
  updated_at: string;
}
