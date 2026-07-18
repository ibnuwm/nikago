'use client';

import type { SubscriptionHistory as SubscriptionHistoryType } from '@/types';

interface SubscriptionHistoryProps {
  histories: SubscriptionHistoryType[];
}

function formatDate(dateStr: string): string {
  const d = new Date(dateStr);
  return d.toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' });
}

const actionLabels: Record<string, string> = {
  subscribed: 'Berlangganan',
  upgraded: 'Upgrade Paket',
  downgraded: 'Downgrade Paket',
  cancelled: 'Dibatalkan',
  renewed: 'Diperpanjang',
};

export function SubscriptionHistory({ histories }: SubscriptionHistoryProps) {
  if (histories.length === 0) {
    return (
      <div className="py-8 text-center">
        <p className="text-sm text-muted-foreground">Belum ada riwayat langganan.</p>
      </div>
    );
  }

  return (
    <div className="space-y-4">
      {histories.map((history) => (
        <div key={history.id} className="rounded-lg border bg-card p-4">
          <div className="flex items-start justify-between">
            <div>
              <p className="text-sm font-medium text-card-foreground">
                {actionLabels[history.action] ?? history.action}
              </p>
              {history.plan && (
                <p className="text-xs text-muted-foreground">
                  Paket: {history.plan.name}
                </p>
              )}
              {history.old_plan && (
                <p className="text-xs text-muted-foreground">
                  Dari: {history.old_plan.name}
                </p>
              )}
              {history.notes && (
                <p className="mt-1 text-xs text-muted-foreground">{history.notes}</p>
              )}
            </div>
            <p className="shrink-0 text-xs text-muted-foreground">
              {formatDate(history.created_at)}
            </p>
          </div>
        </div>
      ))}
    </div>
  );
}
