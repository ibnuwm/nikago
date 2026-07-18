'use client';

import { useEffect, useState, useCallback } from 'react';
import { useRouter } from 'next/navigation';
import { useAuthStore } from '@/stores/auth-store';
import { useUser } from '@/hooks/use-auth';
import { AnalyticsSummaryCard } from '@/features/analytics/components/analytics-summary-card';
import { AnalyticsTrendChart } from '@/features/analytics/components/analytics-trend-chart';
import { AnalyticsFilter } from '@/features/analytics/components/analytics-filter';
import { AnalyticsExportButton } from '@/features/analytics/components/analytics-export-button';
import {
  useDashboardAnalytics,
  useInvitationAnalytics,
  useRsvpAnalytics,
  useGuestAnalytics,
  useVendorAnalytics,
  useSubscriptionAnalytics,
  useRevenueAnalytics,
  useTrafficAnalytics,
  useAiAnalytics,
} from '@/features/analytics/hooks/use-analytics';
import {
  BarChart3,
  Mail,
  Users,
  UserCheck,
  Store,
  CreditCard,
  DollarSign,
  Activity,
  Cpu,
} from 'lucide-react';

type Tab = 'dashboard' | 'invitations' | 'rsvp' | 'guests' | 'vendors' | 'subscriptions' | 'revenue' | 'traffic' | 'ai';

const TABS: { key: Tab; label: string; icon: React.ReactNode }[] = [
  { key: 'dashboard', label: 'Dashboard', icon: <BarChart3 className="w-4 h-4" /> },
  { key: 'invitations', label: 'Invitations', icon: <Mail className="w-4 h-4" /> },
  { key: 'rsvp', label: 'RSVP', icon: <UserCheck className="w-4 h-4" /> },
  { key: 'guests', label: 'Guests', icon: <Users className="w-4 h-4" /> },
  { key: 'vendors', label: 'Vendors', icon: <Store className="w-4 h-4" /> },
  { key: 'subscriptions', label: 'Subscriptions', icon: <CreditCard className="w-4 h-4" /> },
  { key: 'revenue', label: 'Revenue', icon: <DollarSign className="w-4 h-4" /> },
  { key: 'traffic', label: 'Traffic', icon: <Activity className="w-4 h-4" /> },
  { key: 'ai', label: 'AI Usage', icon: <Cpu className="w-4 h-4" /> },
];

export default function AnalyticsPage() {
  const [tab, setTab] = useState<Tab>('dashboard');
  const [filters, setFilters] = useState<{ start_date?: string; end_date?: string }>({});
  const token = useAuthStore((s) => s.token);
  const router = useRouter();
  const { data: user, isLoading: isUserLoading } = useUser();

  const handleFilter = useCallback((newFilters: { start_date?: string; end_date?: string }) => {
    setFilters(newFilters);
  }, []);

  useEffect(() => {
    if (!token && !isUserLoading) {
      router.push('/login');
    }
  }, [token, isUserLoading, router]);

  if (!token || !user) return null;

  return (
    <div className="min-h-screen bg-gray-50">
      <div className="max-w-7xl mx-auto px-4 py-6">
        <div className="flex items-center justify-between mb-6">
          <div>
            <h1 className="text-2xl font-bold text-gray-900">Analytics</h1>
            <p className="text-sm text-gray-500">Platform performance and business metrics</p>
          </div>
          <AnalyticsExportButton type={tab} filters={filters} />
        </div>

        <div className="mb-4">
          <AnalyticsFilter onApply={handleFilter} />
        </div>

        <div className="flex space-x-1 border-b mb-6 overflow-x-auto">
          {TABS.map((t) => (
            <button
              key={t.key}
              onClick={() => setTab(t.key)}
              className={`flex items-center gap-1.5 px-3 py-2 text-sm font-medium border-b-2 transition-colors whitespace-nowrap ${
                tab === t.key
                  ? 'border-blue-600 text-blue-600'
                  : 'border-transparent text-gray-500 hover:text-gray-700'
              }`}
            >
              {t.icon}
              {t.label}
            </button>
          ))}
        </div>

        {tab === 'dashboard' && <DashboardSection filters={filters} />}
        {tab === 'invitations' && <InvitationSection filters={filters} />}
        {tab === 'rsvp' && <RsvpSection filters={filters} />}
        {tab === 'guests' && <GuestSection filters={filters} />}
        {tab === 'vendors' && <VendorSection filters={filters} />}
        {tab === 'subscriptions' && <SubscriptionSection filters={filters} />}
        {tab === 'revenue' && <RevenueSection filters={filters} />}
        {tab === 'traffic' && <TrafficSection filters={filters} />}
        {tab === 'ai' && <AiSection filters={filters} />}
      </div>
    </div>
  );
}

function DashboardSection({ filters }: { filters: { start_date?: string; end_date?: string } }) {
  const { data, isLoading } = useDashboardAnalytics(filters);

  return (
    <div className="space-y-6">
      <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
        <AnalyticsSummaryCard title="Total Users" value={data?.total_users ?? 0} loading={isLoading} />
        <AnalyticsSummaryCard title="Active Users" value={data?.active_users ?? 0} loading={isLoading} />
        <AnalyticsSummaryCard title="New Users" value={data?.new_users ?? 0} subtitle="This period" loading={isLoading} />
        <AnalyticsSummaryCard title="Total Vendors" value={data?.total_vendors ?? 0} loading={isLoading} />
        <AnalyticsSummaryCard title="Verified Vendors" value={data?.verified_vendors ?? 0} loading={isLoading} />
        <AnalyticsSummaryCard title="Active Subscriptions" value={data?.active_subscriptions ?? 0} loading={isLoading} />
        <AnalyticsSummaryCard
          title="Total Revenue"
          value={`Rp ${(data?.total_revenue ?? 0).toLocaleString()}`}
          trend={data?.growth.revenue_percentage}
          loading={isLoading}
        />
        <AnalyticsSummaryCard
          title="MRR"
          value={`Rp ${(data?.mrr ?? 0).toLocaleString()}`}
          subtitle={`ARR: Rp ${(data?.arr ?? 0).toLocaleString()}`}
          loading={isLoading}
        />
      </div>
      <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div className="bg-white rounded-lg border p-4">
          <p className="text-sm font-medium text-gray-700 mb-2">AI Usage</p>
          <p className="text-xs text-gray-500">Tokens: {(data?.total_ai_tokens ?? 0).toLocaleString()}</p>
          <p className="text-xs text-gray-500">Cost: {(data?.total_ai_cost ?? 0).toLocaleString()}</p>
        </div>
      </div>
    </div>
  );
}

function InvitationSection({ filters }: { filters: { start_date?: string; end_date?: string } }) {
  const { data, isLoading } = useInvitationAnalytics(filters);

  return (
    <div className="space-y-6">
      <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
        <AnalyticsSummaryCard title="Total" value={data?.total_invitations ?? 0} loading={isLoading} />
        <AnalyticsSummaryCard title="Published" value={data?.published ?? 0} loading={isLoading} />
        <AnalyticsSummaryCard title="Draft" value={data?.draft ?? 0} loading={isLoading} />
      </div>
      {data?.by_status && (
        <div className="bg-white rounded-lg border p-4">
          <p className="text-sm font-medium text-gray-700 mb-2">By Status</p>
          <div className="space-y-2">
            {Object.entries(data.by_status).map(([status, count]) => (
              <div key={status} className="flex items-center justify-between text-sm">
                <span className="text-gray-600">{status}</span>
                <span className="font-medium">{count}</span>
              </div>
            ))}
          </div>
        </div>
      )}
      <AnalyticsTrendChart data={data?.trend ?? []} loading={isLoading} />
    </div>
  );
}

function RsvpSection({ filters }: { filters: { start_date?: string; end_date?: string } }) {
  const { data, isLoading } = useRsvpAnalytics(filters);

  return (
    <div className="space-y-6">
      <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
        <AnalyticsSummaryCard title="Total Guests" value={data?.total_guests ?? 0} loading={isLoading} />
        <AnalyticsSummaryCard title="Total RSVPs" value={data?.total_rsvps ?? 0} loading={isLoading} />
        <AnalyticsSummaryCard title="Confirmed" value={data?.confirmed ?? 0} loading={isLoading} />
        <AnalyticsSummaryCard title="Declined" value={data?.declined ?? 0} loading={isLoading} />
        <AnalyticsSummaryCard title="Maybe" value={data?.maybe ?? 0} loading={isLoading} />
        <AnalyticsSummaryCard title="RSVP Rate" value={`${data?.rsvp_rate ?? 0}%`} loading={isLoading} />
      </div>
      {data?.by_attendance && (
        <div className="bg-white rounded-lg border p-4">
          <p className="text-sm font-medium text-gray-700 mb-2">By Attendance</p>
          <div className="space-y-2">
            {Object.entries(data.by_attendance).map(([attendance, count]) => (
              <div key={attendance} className="flex items-center justify-between text-sm">
                <span className="text-gray-600">{attendance}</span>
                <span className="font-medium">{count}</span>
              </div>
            ))}
          </div>
        </div>
      )}
      <AnalyticsTrendChart data={data?.trend ?? []} loading={isLoading} />
    </div>
  );
}

function GuestSection({ filters }: { filters: { start_date?: string; end_date?: string } }) {
  const { data, isLoading } = useGuestAnalytics(filters);

  return (
    <div className="space-y-6">
      <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
        <AnalyticsSummaryCard title="Total Guests" value={data?.total_guests ?? 0} loading={isLoading} />
        <AnalyticsSummaryCard title="Invited" value={data?.invited ?? 0} loading={isLoading} />
        <AnalyticsSummaryCard title="Not Invited" value={data?.not_invited ?? 0} loading={isLoading} />
      </div>
      <AnalyticsTrendChart data={data?.trend ?? []} loading={isLoading} />
    </div>
  );
}

function VendorSection({ filters }: { filters: { start_date?: string; end_date?: string } }) {
  const { data, isLoading } = useVendorAnalytics(filters);

  return (
    <div className="space-y-6">
      <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
        <AnalyticsSummaryCard title="Total Vendors" value={data?.total_vendors ?? 0} loading={isLoading} />
        <AnalyticsSummaryCard title="Active" value={data?.active ?? 0} loading={isLoading} />
        <AnalyticsSummaryCard title="Verified" value={data?.verified ?? 0} loading={isLoading} />
        <AnalyticsSummaryCard title="Featured" value={data?.featured ?? 0} loading={isLoading} />
        <AnalyticsSummaryCard title="Avg Rating" value={data?.average_rating ?? 0} loading={isLoading} />
        <AnalyticsSummaryCard title="Vendor Density" value={`${data?.vendor_density ?? 0}%`} loading={isLoading} />
      </div>
      {data?.by_city && Object.keys(data.by_city).length > 0 && (
        <div className="bg-white rounded-lg border p-4">
          <p className="text-sm font-medium text-gray-700 mb-2">By City (Top 10)</p>
          <div className="space-y-2">
            {Object.entries(data.by_city).map(([city, count]) => (
              <div key={city} className="flex items-center justify-between text-sm">
                <span className="text-gray-600">{city}</span>
                <span className="font-medium">{count}</span>
              </div>
            ))}
          </div>
        </div>
      )}
      <AnalyticsTrendChart data={data?.trend ?? []} loading={isLoading} />
    </div>
  );
}

function SubscriptionSection({ filters }: { filters: { start_date?: string; end_date?: string } }) {
  const { data, isLoading } = useSubscriptionAnalytics(filters);

  return (
    <div className="space-y-6">
      <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
        <AnalyticsSummaryCard title="Total" value={data?.total_subscriptions ?? 0} loading={isLoading} />
        <AnalyticsSummaryCard title="Active" value={data?.active ?? 0} loading={isLoading} />
        <AnalyticsSummaryCard title="Expired" value={data?.expired ?? 0} loading={isLoading} />
        <AnalyticsSummaryCard title="Cancelled" value={data?.cancelled ?? 0} loading={isLoading} />
        <AnalyticsSummaryCard title="Churn Rate" value={`${data?.churn_rate ?? 0}%`} loading={isLoading} />
        <AnalyticsSummaryCard
          title="MRR"
          value={`Rp ${(data?.mrr ?? 0).toLocaleString()}`}
          subtitle={`ARR: Rp ${(data?.arr ?? 0).toLocaleString()}`}
          loading={isLoading}
        />
      </div>
      {data?.by_plan && data.by_plan.length > 0 && (
        <div className="bg-white rounded-lg border p-4">
          <p className="text-sm font-medium text-gray-700 mb-2">By Plan</p>
          <div className="space-y-2">
            {data.by_plan.map((item) => (
              <div key={item.plan} className="flex items-center justify-between text-sm">
                <span className="text-gray-600">{item.plan}</span>
                <span className="font-medium">{item.count}</span>
              </div>
            ))}
          </div>
        </div>
      )}
      <AnalyticsTrendChart data={data?.trend ?? []} loading={isLoading} />
    </div>
  );
}

function RevenueSection({ filters }: { filters: { start_date?: string; end_date?: string } }) {
  const { data, isLoading } = useRevenueAnalytics(filters);

  return (
    <div className="space-y-6">
      <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
        <AnalyticsSummaryCard
          title="Total Revenue"
          value={`Rp ${(data?.total_revenue ?? 0).toLocaleString()}`}
          trend={data?.growth_percentage}
          loading={isLoading}
        />
        <AnalyticsSummaryCard title="Transactions" value={data?.total_transactions ?? 0} loading={isLoading} />
        <AnalyticsSummaryCard
          title="Avg Transaction"
          value={`Rp ${(data?.average_transaction_value ?? 0).toLocaleString()}`}
          loading={isLoading}
        />
        <AnalyticsSummaryCard
          title="Refunds"
          value={`Rp ${(data?.refunds ?? 0).toLocaleString()}`}
          loading={isLoading}
        />
      </div>
      {data?.by_method && data.by_method.length > 0 && (
        <div className="bg-white rounded-lg border p-4">
          <p className="text-sm font-medium text-gray-700 mb-2">By Payment Method</p>
          <div className="space-y-2">
            {data.by_method.map((item) => (
              <div key={item.method} className="flex items-center justify-between text-sm">
                <span className="text-gray-600">{item.method}</span>
                <span className="font-medium">{item.count} transactions</span>
              </div>
            ))}
          </div>
        </div>
      )}
      {data?.daily && data.daily.length > 0 && (
        <div className="bg-white rounded-lg border p-4">
          <p className="text-sm font-medium text-gray-700 mb-3">Daily Revenue</p>
          <div className="space-y-1.5">
            {data.daily.slice(-14).map((item) => (
              <div key={item.date} className="flex items-center gap-2">
                <span className="text-xs text-gray-500 w-24 shrink-0">
                  {new Date(item.date).toLocaleDateString('id-ID', { weekday: 'short', month: 'short', day: 'numeric' })}
                </span>
                <div className="flex-1 h-5 bg-gray-100 rounded overflow-hidden">
                  <div
                    className="h-full bg-green-500 rounded transition-all"
                    style={{ width: `${(item.revenue / Math.max(...data.daily.map(d => d.revenue), 1)) * 100}%` }}
                  />
                </div>
                <span className="text-xs text-gray-600 w-20 text-right">
                  Rp {item.revenue.toLocaleString()}
                </span>
              </div>
            ))}
          </div>
        </div>
      )}
    </div>
  );
}

function TrafficSection({ filters }: { filters: { start_date?: string; end_date?: string } }) {
  const { data, isLoading } = useTrafficAnalytics(filters);

  return (
    <div className="space-y-6">
      <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
        <AnalyticsSummaryCard title="Page Views" value={data?.page_views ?? 0} loading={isLoading} />
        <AnalyticsSummaryCard title="Unique Visitors" value={data?.unique_visitors ?? 0} loading={isLoading} />
        <AnalyticsSummaryCard title="Total Events" value={data?.total_events ?? 0} loading={isLoading} />
      </div>
      {data?.by_event_type && (
        <div className="bg-white rounded-lg border p-4">
          <p className="text-sm font-medium text-gray-700 mb-2">By Event Type</p>
          <div className="space-y-2">
            {Object.entries(data.by_event_type).map(([type, count]) => (
              <div key={type} className="flex items-center justify-between text-sm">
                <span className="text-gray-600">{type}</span>
                <span className="font-medium">{count}</span>
              </div>
            ))}
          </div>
        </div>
      )}
    </div>
  );
}

function AiSection({ filters }: { filters: { start_date?: string; end_date?: string } }) {
  const { data, isLoading } = useAiAnalytics(filters);

  return (
    <div className="space-y-6">
      <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
        <AnalyticsSummaryCard title="Total Requests" value={data?.total_requests ?? 0} loading={isLoading} />
        <AnalyticsSummaryCard title="Total Tokens" value={(data?.total_tokens ?? 0).toLocaleString()} loading={isLoading} />
        <AnalyticsSummaryCard title="Total Cost" value={data?.total_cost?.toFixed(6) ?? '0'} loading={isLoading} />
        <AnalyticsSummaryCard title="Avg Tokens/Req" value={data?.average_tokens_per_request ?? 0} loading={isLoading} />
      </div>
      {data?.by_feature && data.by_feature.length > 0 && (
        <div className="bg-white rounded-lg border p-4">
          <p className="text-sm font-medium text-gray-700 mb-2">By Feature</p>
          <div className="space-y-2">
            {data.by_feature.map((item) => (
              <div key={item.feature} className="flex items-center justify-between text-sm">
                <span className="text-gray-600">{item.feature}</span>
                <span className="font-medium">{item.requests} requests, {(item.total_tokens ?? 0).toLocaleString()} tokens</span>
              </div>
            ))}
          </div>
        </div>
      )}
    </div>
  );
}
