'use client';

import { useUser, useLogout } from '@/hooks/use-auth';
import { useDashboard } from '@/features/dashboard/hooks/use-dashboard';
import { useAuthStore } from '@/stores/auth-store';
import { useRouter } from 'next/navigation';
import { useEffect } from 'react';
import { Button } from '@/components/ui/button';
import { SummaryCard } from '@/features/dashboard/components/summary-card';
import { RecentActivity } from '@/features/dashboard/components/recent-activity';
import { ReminderCard } from '@/features/dashboard/components/reminder-card';
import { WeddingProgress } from '@/features/dashboard/components/wedding-progress';

export default function DashboardPage() {
  const { data: user, isLoading: isUserLoading } = useUser();
  const { data: dashboard, isLoading: isDashboardLoading } = useDashboard();
  const token = useAuthStore((s) => s.token);
  const router = useRouter();
  const logout = useLogout();

  useEffect(() => {
    if (!token && !isUserLoading) {
      router.push('/login');
    }
  }, [token, isUserLoading, router]);

  if (isUserLoading || isDashboardLoading) {
    return (
      <div className="flex min-h-screen items-center justify-center">
        <p className="text-sm text-muted-foreground">Loading...</p>
      </div>
    );
  }

  if (!user) {
    return null;
  }

  const stats = dashboard?.statistics;
  const upcoming = dashboard?.upcoming_events;
  const activities = dashboard?.recent_activity ?? [];

  return (
    <div className="min-h-screen bg-background">
      <header className="border-b bg-card">
        <div className="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
          <h1 className="text-xl font-bold text-card-foreground">Nikago</h1>
          <div className="flex items-center gap-4">
            <span className="text-sm text-muted-foreground">{user.name}</span>
            <Button
              variant="outline"
              size="sm"
              onClick={() => logout.mutate()}
              disabled={logout.isPending}
            >
              {logout.isPending ? 'Signing out...' : 'Sign out'}
            </Button>
          </div>
        </div>
      </header>
      <main className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <h2 className="text-2xl font-bold text-foreground">
          Welcome back, {user.name}
        </h2>
        <p className="mt-1 text-sm text-muted-foreground">
          Here&apos;s an overview of your wedding planning.
        </p>

        <div className="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
          <SummaryCard
            title="Guests"
            value={stats?.guests_count ?? 0}
            description="Total invited"
          />
          <SummaryCard
            title="RSVP Confirmed"
            value={stats?.rsvp_confirmed_count ?? 0}
            description={`${stats?.rsvp_pending_count ?? 0} pending`}
          />
          <SummaryCard
            title="Budget"
            value={`$${(stats?.budget_spent ?? 0).toLocaleString()}`}
            description={`of $${(stats?.budget_total ?? 0).toLocaleString()} total`}
          />
          <SummaryCard
            title="Vendors"
            value={stats?.vendors_count ?? 0}
            description="Booked vendors"
          />
        </div>

        <div className="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-3">
          <div className="lg:col-span-2">
            <WeddingProgress
              daysRemaining={upcoming?.days_remaining ?? null}
              weddingDate={upcoming?.wedding_date ?? null}
            />
          </div>
          <div>
            <ReminderCard reminders={upcoming?.reminders ?? []} />
          </div>
        </div>

        <div className="mt-8">
          <RecentActivity activities={activities} />
        </div>
      </main>
    </div>
  );
}
