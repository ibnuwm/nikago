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

export interface InvitationTemplate {
  id: string;
  name: string;
  slug: string;
  category: string;
  description: string | null;
  image: string | null;
  preview_image: string | null;
  is_premium: boolean;
  favorites_count: number;
  is_favorited: boolean;
  created_at: string;
  updated_at: string;
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

export interface Guest {
  id: string;
  wedding_id: number;
  group_id: number | null;
  category_id: number | null;
  name: string;
  phone: string | null;
  email: string | null;
  address: string | null;
  pax: number;
  qr_code: string | null;
  invitation_sent_at: string | null;
  status: string;
  rsvp?: {
    status: string;
    total_guests: number;
  } | null;
  created_at: string;
  updated_at: string;
}

export interface GuestFormData {
  wedding_id: number;
  group_id?: number | null;
  category_id?: number | null;
  name: string;
  phone?: string | null;
  email?: string | null;
  address?: string | null;
  pax?: number;
  status?: string;
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

export interface PlannerProgress {
  progress: number;
  completed_task: number;
  total_task: number;
}

export interface PlannerSummary {
  wedding_title: string | null;
  wedding_status: string | null;
  progress: number;
  completed_task: number;
  total_task: number;
  guests_count: number;
  checklist_count: number;
  budget_total: number;
  budget_spent: number;
  timeline_count: number;
  reminder_count: number;
}

export interface PlannerData {
  wedding: Wedding | null;
  progress: PlannerProgress;
  summary: PlannerSummary;
}

export interface PlannerExportData {
  wedding_title: string;
  exported_at: string;
  progress: PlannerProgress;
  summary: PlannerSummary;
}

export interface AiPlannerData {
  checklists: unknown[];
  timelines: unknown[];
  budgets: unknown[];
}

export interface ChecklistItem {
  id: string;
  checklist_id: number;
  title: string;
  priority: 'low' | 'medium' | 'high';
  due_date: string | null;
  completed_at: string | null;
  sort_order: number;
  created_at: string;
  updated_at: string;
}

export interface Checklist {
  id: string;
  wedding_id: number;
  title: string;
  description: string | null;
  progress: number;
  items?: ChecklistItem[];
  created_at: string;
  updated_at: string;
}

export interface ChecklistFormData {
  wedding_id: number;
  title: string;
  description?: string | null;
}

export interface ReorderItem {
  uuid: string;
  sort_order: number;
}

export interface TimelineTask {
  id: string;
  timeline_id: number;
  title: string;
  description: string | null;
  priority: 'low' | 'medium' | 'high';
  start_date: string | null;
  due_date: string | null;
  duration_days: number;
  completed_at: string | null;
  sort_order: number;
  created_at: string;
  updated_at: string;
}

export interface Timeline {
  id: string;
  wedding_id: number;
  title: string;
  description: string | null;
  progress: number;
  completed_at: string | null;
  tasks?: TimelineTask[];
  created_at: string;
  updated_at: string;
}

export interface SeatingTable {
  id: string;
  wedding_id: number;
  name: string;
  capacity: number;
  shape?: string;
  position_x?: number;
  position_y?: number;
  sort_order: number;
  assigned_count: number;
  guests?: SeatedGuest[];
  created_at: string;
  updated_at: string;
}

export interface SeatedGuest {
  id: string;
  table_id: number;
  guest_id: number;
  guest_name?: string;
  seat_number?: number;
  notes?: string;
  created_at: string;
  updated_at: string;
}

export interface SeatingTableFormData {
  wedding_id: number;
  name: string;
  capacity?: number;
  shape?: string;
  position_x?: number;
  position_y?: number;
  sort_order?: number;
}

export interface SeatAssignmentFormData {
  guest_id: string;
  seat_number?: number;
  notes?: string;
}

export interface SeatingPreview {
  tables: {
    id: string;
    name: string;
    capacity: number;
    shape?: string;
    position_x?: number;
    position_y?: number;
    assigned_count: number;
    guests: { name?: string; seat_number?: number; notes?: string }[];
  }[];
  total_tables: number;
  total_guests: number;
  total_capacity: number;
}

export interface TimelineFormData {
  wedding_id: number;
  title: string;
  description?: string | null;
  tasks?: {
    title: string;
    description?: string | null;
    priority?: 'low' | 'medium' | 'high';
    start_date?: string | null;
    due_date?: string | null;
    duration_days?: number;
    sort_order?: number;
  }[];
}
