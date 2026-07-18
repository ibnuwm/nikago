'use client';

import { useEffect } from 'react';
import { useRouter } from 'next/navigation';
import { useAuthStore } from '@/stores/auth-store';
import { useUser } from '@/hooks/use-auth';
import { NotificationCenter } from '@/features/notification/components/notification-center';
import {
  useNotifications,
  useMarkAsRead,
  useMarkAllAsRead,
  useDeleteNotification,
} from '@/features/notification/hooks/use-notification';

export default function NotificationsPage() {
  const token = useAuthStore((s) => s.token);
  const router = useRouter();
  const { data: user, isLoading: isUserLoading } = useUser();

  useEffect(() => {
    if (!token && !isUserLoading) {
      router.push('/login');
    }
  }, [token, isUserLoading, router]);

  const { data: notificationsData, isLoading } = useNotifications();
  const markAsRead = useMarkAsRead();
  const markAllAsRead = useMarkAllAsRead();
  const deleteNotification = useDeleteNotification();

  if (!token || !user) return null;

  const notifications = notificationsData?.data ?? [];

  return (
    <div className="min-h-screen bg-background">
      <header className="border-b bg-card">
        <div className="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
          <h1 className="text-xl font-bold text-card-foreground">Notifikasi</h1>
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

        {isLoading ? (
          <div className="py-12 text-center">
            <p className="text-sm text-muted-foreground">Memuat notifikasi...</p>
          </div>
        ) : (
          <NotificationCenter
            notifications={notifications}
            onMarkAsRead={(uuid) => markAsRead.mutate(uuid)}
            onMarkAllAsRead={() => markAllAsRead.mutate()}
            onDelete={(uuid) => deleteNotification.mutate(uuid)}
          />
        )}
      </main>
    </div>
  );
}
