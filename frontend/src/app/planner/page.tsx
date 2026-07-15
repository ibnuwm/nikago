'use client';

import { useUser } from '@/hooks/use-auth';
import { usePlannerDashboard } from '@/features/planner/hooks/use-planner';
import { PlannerDashboard } from '@/features/planner/components/planner-dashboard';
import { useAuthStore } from '@/stores/auth-store';
import { useRouter } from 'next/navigation';
import { useEffect } from 'react';
import { Button } from '@/components/ui/button';

export default function PlannerPage() {
  const { data: user, isLoading: isUserLoading } = useUser();
  const { data: planner, isLoading: isPlannerLoading } = usePlannerDashboard();
  const token = useAuthStore((s) => s.token);
  const router = useRouter();

  useEffect(() => {
    if (!token && !isUserLoading) {
      router.push('/login');
    }
  }, [token, isUserLoading, router]);

  if (isUserLoading || isPlannerLoading) {
    return (
      <div className="flex min-h-screen items-center justify-center">
        <p className="text-sm text-muted-foreground">Loading...</p>
      </div>
    );
  }

  if (!user) {
    return null;
  }

  return (
    <div className="min-h-screen bg-background">
      <header className="border-b bg-card">
        <div className="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
          <h1 className="text-xl font-bold text-card-foreground">Wedding Planner</h1>
          <div className="flex items-center gap-4">
            <span className="text-sm text-muted-foreground">{user.name}</span>
            <a
              href="/dashboard"
              className="inline-flex h-7 shrink-0 items-center justify-center gap-1 rounded-[min(var(--radius-md),12px)] border border-border bg-background px-2.5 text-[0.8rem] font-medium whitespace-nowrap text-foreground transition-all outline-none select-none hover:bg-muted hover:text-foreground"
            >
              Back to Dashboard
            </a>
          </div>
        </div>
      </header>
      <main className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <h2 className="text-2xl font-bold text-foreground">
          {planner?.wedding?.title ? planner.wedding.title : 'Wedding Planner'}
        </h2>
        <p className="mt-1 text-sm text-muted-foreground">
          Plan and track your wedding preparation progress.
        </p>

        {planner && <PlannerDashboard data={planner} />}
      </main>
    </div>
  );
}
