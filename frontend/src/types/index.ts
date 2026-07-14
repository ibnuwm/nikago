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
  name: string;
  email: string;
  avatar?: string;
  phone?: string;
  status: string;
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

export interface LoginData {
  email: string;
  password: string;
  remember?: boolean;
}

export interface RegisterData {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
}

export interface AuthResponse {
  user: User;
  token: string;
}

export interface DashboardStatistics {
  invitations_count: number;
  guests_count: number;
  rsvp_pending_count: number;
  rsvp_confirmed_count: number;
  budget_total: number;
  budget_spent: number;
  vendors_count: number;
}

export interface RecentActivity {
  id: string;
  type: string;
  title: string;
  description: string | null;
  created_at: string;
}

export interface TimelineEvent {
  id: string;
  title: string;
  date: string;
  type: string;
}

export interface Reminder {
  id: string;
  title: string;
  date: string;
  type: string;
}

export interface UpcomingEvents {
  wedding_date: string | null;
  days_remaining: number | null;
  timeline_events: TimelineEvent[];
  reminders: Reminder[];
}

export interface DashboardData {
  user: User;
  wedding: Wedding | null;
  subscription: Subscription | null;
  statistics: DashboardStatistics;
  recent_activity: RecentActivity[];
  upcoming_events: UpcomingEvents;
}

export interface Wedding {
  id: string;
  title: string;
  slug: string;
  status: string;
  theme: string | null;
  cover_image: string | null;
  published_at: string | null;
  created_at: string;
  updated_at: string;
}

export interface WeddingFormData {
  title: string;
  theme?: string | null;
  cover_image?: string | null;
}

export interface Invitation {
  id: string;
  title: string;
  slug: string;
  description: string | null;
  cover_image: string | null;
  status: string;
  published_at: string | null;
  wedding_id: string | null;
  created_at: string;
  updated_at: string;
}

export interface InvitationFormData {
  wedding_id: string;
  title: string;
  slug?: string;
  cover_image?: string | null;
  description?: string | null;
}

export interface Subscription {
  id: string;
  plan: string;
  status: string;
  expires_at: string | null;
}

export interface CmsFaq {
  id: string;
  question: string;
  answer: string;
  category: string | null;
  sort_order: number;
  created_at: string;
  updated_at: string;
}

export interface CmsBanner {
  id: string;
  title: string;
  subtitle: string | null;
  image: string | null;
  link: string | null;
  sort_order: number;
  created_at: string;
  updated_at: string;
}

export interface CmsPage {
  id: string;
  title: string;
  slug: string;
  content: string | null;
  meta_title: string | null;
  meta_description: string | null;
  status: string;
  created_at: string;
  updated_at: string;
}
