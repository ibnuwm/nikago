'use client';

import type { Notification } from '@/types';

interface NotificationCenterProps {
  notifications: Notification[];
  onMarkAsRead: (uuid: string) => void;
  onMarkAllAsRead: () => void;
  onDelete: (uuid: string) => void;
}

const channelLabels: Record<string, string> = {
  in_app: 'In-App',
  email: 'Email',
  whatsapp: 'WhatsApp',
};

function formatDate(dateStr: string | null): string {
  if (!dateStr) return '-';
  const d = new Date(dateStr);
  const now = new Date();
  const diffMs = now.getTime() - d.getTime();
  const diffMins = Math.floor(diffMs / 60000);
  const diffHours = Math.floor(diffMs / 3600000);
  const diffDays = Math.floor(diffMs / 86400000);

  if (diffMins < 1) return 'Baru saja';
  if (diffMins < 60) return `${diffMins} menit lalu`;
  if (diffHours < 24) return `${diffHours} jam lalu`;
  if (diffDays < 7) return `${diffDays} hari lalu`;

  return d.toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  });
}

export function NotificationCenter({
  notifications,
  onMarkAsRead,
  onMarkAllAsRead,
  onDelete,
}: NotificationCenterProps) {
  return (
    <div className="space-y-4">
      <div className="flex items-center justify-between">
        <h2 className="text-lg font-semibold">Notifikasi</h2>
        <button
          onClick={onMarkAllAsRead}
          className="rounded-md border bg-background px-3 py-1.5 text-xs font-medium transition-colors hover:bg-muted"
        >
          Tandai Semua Dibaca
        </button>
      </div>

      {notifications.length === 0 ? (
        <div className="py-12 text-center">
          <p className="text-sm text-muted-foreground">Tidak ada notifikasi.</p>
        </div>
      ) : (
        <div className="space-y-2">
          {notifications.map((notif) => (
            <div
              key={notif.id}
              className={`rounded-lg border bg-card p-4 transition-colors ${
                !notif.is_read ? 'border-l-2 border-l-primary' : ''
              }`}
            >
              <div className="flex items-start justify-between gap-4">
                <div className="min-w-0 flex-1">
                  <div className="flex items-center gap-2">
                    {!notif.is_read && (
                      <span className="h-2 w-2 shrink-0 rounded-full bg-primary" />
                    )}
                    <p className="text-sm font-medium text-card-foreground">
                      {notif.title}
                    </p>
                  </div>
                  {notif.message && (
                    <p className="mt-1 text-xs text-muted-foreground line-clamp-2">
                      {notif.message}
                    </p>
                  )}
                  <div className="mt-2 flex items-center gap-2 text-[0.7rem] text-muted-foreground">
                    <span className="rounded bg-muted px-1.5 py-0.5">
                      {channelLabels[notif.channel] ?? notif.channel}
                    </span>
                    <span>{formatDate(notif.created_at)}</span>
                  </div>
                </div>
                <div className="flex shrink-0 items-center gap-1">
                  {!notif.is_read && (
                    <button
                      onClick={() => onMarkAsRead(notif.id)}
                      className="rounded px-2 py-1 text-xs transition-colors hover:bg-muted"
                      title="Tandai dibaca"
                    >
                      ✓
                    </button>
                  )}
                  <button
                    onClick={() => onDelete(notif.id)}
                    className="rounded px-2 py-1 text-xs transition-colors hover:bg-muted"
                    title="Hapus"
                  >
                    ✕
                  </button>
                </div>
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  );
}
