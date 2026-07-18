'use client';

import { useEffect, useState } from 'react';
import { useRouter } from 'next/navigation';
import { useAuthStore } from '@/stores/auth-store';
import { useUser } from '@/hooks/use-auth';
import { NotificationSettings } from '@/features/notification/components/notification-settings';

export default function NotificationSettingsPage() {
  const token = useAuthStore((s) => s.token);
  const router = useRouter();
  const { data: user, isLoading: isUserLoading } = useUser();

  const [preferences, setPreferences] = useState({
    in_app: true,
    email: true,
    whatsapp: false,
  });

  useEffect(() => {
    if (!token && !isUserLoading) {
      router.push('/login');
    }
  }, [token, isUserLoading, router]);

  if (!token || !user) return null;

  const handleToggle = (channel: 'in_app' | 'email' | 'whatsapp') => {
    setPreferences((prev) => ({
      ...prev,
      [channel]: !prev[channel],
    }));
  };

  return (
    <div className="min-h-screen bg-background">
      <header className="border-b bg-card">
        <div className="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
          <h1 className="text-xl font-bold text-card-foreground">Pengaturan Notifikasi</h1>
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

      <main className="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <div className="mb-6 flex gap-3">
          <a
            href="/notifications"
            className="rounded-md border bg-background px-3 py-1.5 text-xs font-medium transition-colors hover:bg-muted"
          >
            Notifikasi
          </a>
          <a
            href="/notifications/settings"
            className="rounded-md border bg-background px-3 py-1.5 text-xs font-medium transition-colors hover:bg-muted"
          >
            Pengaturan
          </a>
        </div>

        <NotificationSettings preferences={preferences} onToggle={handleToggle} />
      </main>
    </div>
  );
}
