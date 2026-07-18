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

export interface BlogAuthor {
  id: string;
  name: string;
  avatar: string | null;
}

export interface BlogCategoryItem {
  id: string;
  name: string;
  slug: string;
  description: string | null;
  post_count: number;
  created_at: string;
  updated_at: string;
}

export interface BlogTagItem {
  id: string;
  name: string;
  slug: string;
  post_count: number;
  created_at: string;
  updated_at: string;
}

export interface BlogPost {
  id: string;
  title: string;
  slug: string;
  excerpt: string | null;
  content: string | null;
  featured_image: string | null;
  author: BlogAuthor | null;
  category: { id: string; name: string; slug: string } | null;
  tags: { id: string; name: string; slug: string }[];
  status: string;
  published_at: string | null;
  seo_title: string | null;
  seo_description: string | null;
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
  priority: "low" | "medium" | "high";
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
  priority: "low" | "medium" | "high";
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
    priority?: "low" | "medium" | "high";
    start_date?: string | null;
    due_date?: string | null;
    duration_days?: number;
    sort_order?: number;
  }[];
}

export interface VendorService {
  id: number;
  name: string;
  description: string | null;
  starting_price: number | null;
  created_at: string;
  updated_at: string;
}

export interface VendorGallery {
  id: number;
  image_url: string;
  caption: string | null;
  sort_order: number;
  created_at: string;
  updated_at: string;
}

export interface VendorPortfolio {
  id: number;
  title: string;
  description: string | null;
  image_url: string;
  created_at: string;
  updated_at: string;
}

export interface VendorPackage {
  id: number;
  name: string;
  description: string | null;
  price: number;
  inclusions: string[] | null;
  sort_order: number;
  created_at: string;
  updated_at: string;
}

export interface Vendor {
  id: string;
  business_name: string;
  slug: string;
  description: string | null;
  phone: string | null;
  email: string | null;
  address: string | null;
  city: string | null;
  province: string | null;
  logo: string | null;
  cover: string | null;
  operating_hours: Record<string, { open: string; close: string }> | null;
  social_media: Record<string, string> | null;
  status: string;
  rating: number;
  total_review: number;
  verified_at: string | null;
  featured: boolean;
  featured_at: string | null;
  is_wishlisted?: boolean;
  services?: VendorService[];
  packages?: VendorPackage[];
  portfolios?: VendorPortfolio[];
  galleries?: VendorGallery[];
  teams?: unknown[];
  created_at: string;
  updated_at: string;
}

export interface VendorFormData {
  business_name: string;
  description?: string | null;
  phone?: string | null;
  email?: string | null;
  address?: string | null;
  city?: string | null;
  province?: string | null;
  logo?: string | null;
  cover?: string | null;
  operating_hours?: Record<string, { open: string; close: string }> | null;
  social_media?: Record<string, string> | null;
  services?: {
    name: string;
    description?: string | null;
    starting_price?: number | null;
  }[];
}

export interface VendorStatistics {
  total_services: number;
  total_packages: number;
  total_portfolios: number;
  total_galleries: number;
  total_teams: number;
  total_documents: number;
  average_service_price: number | null;
  rating: number;
  total_review: number;
  verified: boolean;
}

export interface VendorFilters {
  search?: string;
  category?: string;
  verified?: boolean;
  min_rating?: number;
  sort?: string;
  direction?: string;
}

export interface Category {
  name: string;
  vendor_count: number;
}

export interface MarketplaceFilters {
  category?: string;
  city?: string;
  min_rating?: number;
  verified?: boolean;
  min_price?: number;
  max_price?: number;
  sort?: string;
  direction?: string;
  per_page?: number;
}

export interface CompareRequest {
  vendor_uuids: string[];
}

export interface BookingItem {
  id: number;
  name: string;
  price: number;
  quantity: number;
}

export interface BookingHistory {
  id: number;
  status_from: string | null;
  status_to: string;
  notes: string | null;
  changed_by: number | null;
  created_at: string;
}

export interface BookingDocument {
  id: number;
  type: string;
  file_url: string;
  notes: string | null;
  created_at: string;
}

export interface Booking {
  id: string;
  vendor_uuid: string | null;
  vendor_name: string | null;
  package_name: string | null;
  package_price: number | null;
  booking_date: string;
  event_date: string;
  subtotal: number;
  discount: number;
  total: number;
  status: string;
  notes: string | null;
  vendor?: Vendor;
  package?: VendorPackage;
  items?: BookingItem[];
  histories?: BookingHistory[];
  documents?: BookingDocument[];
  created_at: string;
  updated_at: string;
}

export interface BookingFormData {
  vendor_uuid: string;
  package_id: number;
  event_date: string;
  wedding_id: number;
  notes?: string;
}

export interface CalendarEvent {
  id: string;
  vendor_name: string | null;
  package_name: string | null;
  event_date: string;
  status: string;
}

export interface ReviewImage {
  id: number;
  image_url: string;
  sort_order: number;
}

export interface Review {
  id: string;
  user_id: number;
  user_name: string | null;
  vendor_id: number;
  vendor_uuid: string | null;
  vendor_name: string | null;
  booking_id: number;
  booking_uuid: string | null;
  rating: number;
  review: string | null;
  reply: string | null;
  replied_at: string | null;
  status: string;
  images?: ReviewImage[];
  created_at: string;
  updated_at: string;
}

export interface ReviewFormData {
  booking_uuid: string;
  rating: number;
  review?: string | null;
  images?: string[];
}

export interface ReviewReport {
  id: string;
  review_id: number;
  reason: string;
  status: string;
  created_at: string;
}

export interface SubscriptionPlan {
  id: number;
  code: string;
  name: string;
  description: string;
  monthly_price: number;
  yearly_price: number | null;
  is_active: boolean;
  sort_order: number;
  features: SubscriptionFeature[];
  limits: FeatureLimit[];
}

export interface SubscriptionFeature {
  code: string;
  name: string;
  description: string;
}

export interface FeatureLimit {
  feature_code: string;
  limit_value: number;
}

export interface Subscription {
  id: string;
  plan_id: number;
  plan: SubscriptionPlan | null;
  status: string;
  started_at: string | null;
  expired_at: string | null;
  trial_ends_at: string | null;
  auto_renew: boolean;
  cancelled_at: string | null;
  created_at: string | null;
  histories?: SubscriptionHistory[];
}

export interface SubscriptionHistory {
  id: number;
  action: string;
  notes: string | null;
  plan: { code: string; name: string } | null;
  old_plan: { code: string; name: string } | null;
  created_at: string;
}

export interface PaymentMethodInfo {
  code: string;
  name: string;
  provider: string;
}

export interface Payment {
  id: string;
  invoice_number: string;
  amount: number;
  status: string;
  payment_method: PaymentMethodInfo | null;
  paid_at: string | null;
  expired_at: string | null;
  notes: string | null;
  items: PaymentItem[];
  refunds: RefundItem[];
  created_at: string | null;
}

export interface PaymentItem {
  id: number;
  item_type: string;
  item_id: number | null;
  name: string;
  amount: number;
  quantity: number;
}

export interface RefundItem {
  id: string;
  amount: number;
  reason: string;
  status: string;
  created_at: string | null;
}

export interface Notification {
  id: string;
  type: string;
  title: string;
  message: string | null;
  channel: string;
  is_read: boolean;
  read_at: string | null;
  data: Record<string, unknown> | null;
  created_at: string | null;
}

export interface NotificationTemplate {
  id: string;
  code: string;
  name: string;
  channel: string;
  subject: string | null;
  content: string;
  variables: string[] | null;
  is_active: boolean;
  created_at: string | null;
}

export interface UnreadCount {
  count: number;
}

export interface Lead {
  id: string;
  name: string;
  email: string | null;
  phone: string | null;
  source: string | null;
  stage: string;
  deal_value: number | null;
  notes: string | null;
  assigned_to: { id: string; name: string } | null;
  follow_ups: LeadFollowUp[];
  activities: LeadActivity[];
  closed_at: string | null;
  created_at: string | null;
  updated_at: string | null;
}

export interface LeadFollowUp {
  id: string;
  type: string;
  notes: string;
  follow_up_date: string | null;
  is_completed: boolean;
  completed_at: string | null;
  created_at: string | null;
}

export interface LeadActivity {
  id: string;
  type: string;
  description: string;
  metadata: Record<string, unknown> | null;
  created_at: string | null;
}

export interface Pipeline {
  id: string;
  name: string;
  label: string;
  count: number;
  value: number;
}

export interface LeadFormData {
  name: string;
  email?: string | null;
  phone?: string | null;
  source?: string | null;
  deal_value?: number | null;
  notes?: string | null;
}

export interface FollowUpFormData {
  type: string;
  notes: string;
  follow_up_date?: string | null;
}

export interface CrmStatistics {
  total_leads: number;
  won: number;
  lost: number;
  active: number;
  total_value: number;
  won_value: number;
  conversion_rate: number;
}

export interface AiMessage {
  role: 'system' | 'user' | 'assistant';
  content: string;
}

export interface AiChatRequest {
  messages: AiMessage[];
  model?: string;
  temperature?: number;
}

export interface AiChatResponse {
  content: string;
  model: string;
  prompt_tokens: number;
  completion_tokens: number;
  total_tokens: number;
}

export interface AiGenerateRequest {
  prompt: string;
  model?: string;
}

export interface AiGenerateResponse {
  content: string;
  model: string;
  prompt_tokens: number;
  completion_tokens: number;
  total_tokens: number;
}

export interface AiHistoryItem {
  id: string;
  feature: string;
  prompt: string;
  response: string | null;
  model: string;
  prompt_tokens: number;
  completion_tokens: number;
  created_at: string | null;
}

export interface AiModel {
  id: string;
  name: string;
  description: string;
  context_length: number;
  pricing: {
    prompt: number;
    completion: number;
  };
}

export interface AiUsage {
  total_tokens: number;
  total_cost: number;
  total_requests: number;
  usage_by_feature: Array<{
    feature: string;
    total_tokens: number;
    cost: number;
    requests: number;
  }>;
}

export interface AiContext {
  weddingDate?: string;
  guestCount?: number;
  budget?: number;
  partnerName1?: string;
  partnerName2?: string;
  theme?: string;
  location?: string;
}

export interface AnalyticsDashboard {
  total_users: number;
  active_users: number;
  new_users: number;
  total_vendors: number;
  verified_vendors: number;
  total_revenue: number;
  mrr: number;
  arr: number;
  active_subscriptions: number;
  total_ai_tokens: number;
  total_ai_cost: number;
  growth: {
    revenue: number;
    revenue_percentage: number;
  };
}

export interface AnalyticsInvitation {
  total_invitations: number;
  published: number;
  draft: number;
  by_status: Record<string, number>;
  trend: AnalyticsTrend[];
}

export interface AnalyticsRsvp {
  total_guests: number;
  total_rsvps: number;
  confirmed: number;
  declined: number;
  maybe: number;
  rsvp_rate: number;
  by_attendance: Record<string, number>;
  trend: AnalyticsTrend[];
}

export interface AnalyticsGuest {
  total_guests: number;
  invited: number;
  not_invited: number;
  by_status: Record<string, number>;
  trend: AnalyticsTrend[];
}

export interface AnalyticsVendor {
  total_vendors: number;
  active: number;
  inactive: number;
  verified: number;
  featured: number;
  average_rating: number;
  new_vendors: number;
  vendor_density: number;
  by_city: Record<string, number>;
  trend: AnalyticsTrend[];
}

export interface AnalyticsSubscription {
  total_subscriptions: number;
  active: number;
  expired: number;
  cancelled: number;
  trialing: number;
  new_subscriptions: number;
  churn_rate: number;
  mrr: number;
  arr: number;
  by_plan: Array<{ plan: string; count: number }>;
  trend: AnalyticsTrend[];
}

export interface AnalyticsRevenue {
  total_revenue: number;
  total_transactions: number;
  average_transaction_value: number;
  growth_percentage: number;
  refunds: number;
  by_method: Array<{ method: string; total: number; count: number }>;
  daily: Array<{ date: string; revenue: number; transactions: number }>;
}

export interface AnalyticsTraffic {
  page_views: number;
  unique_visitors: number;
  total_events: number;
  by_event_type: Record<string, number>;
  daily: Array<{ date: string; views: number; visitors: number }>;
}

export interface AnalyticsAi {
  total_requests: number;
  total_tokens: number;
  total_cost: number;
  average_tokens_per_request: number;
  average_cost_per_request: number;
  by_feature: Array<{
    feature: string;
    total_tokens: number;
    cost: number;
    requests: number;
  }>;
  daily: Array<{ date: string; tokens: number; cost: number; requests: number }>;
}

export interface AnalyticsExport {
  report_id: number;
  type: string;
  format: string;
  headers: string[];
  data: string[][];
  status: string;
}

export interface AnalyticsTrend {
  date: string;
  count: number;
}
