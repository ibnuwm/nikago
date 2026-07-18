'use client';

import { Button } from '@/components/ui/button';
import type { Subscription } from '@/types';

interface CurrentSubscriptionProps {
  subscription: Subscription;
  onCancel: () => void;
  onUpgrade: () => void;
  onDowngrade: () => void;
  isCancelling?: boolean;
}

function formatDate(dateStr: string | null): string {
  if (!dateStr) return '-';
  const d = new Date(dateStr);
  return d.toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' });
}

const statusColors: Record<string, string> = {
  active: 'bg-green-100 text-green-800',
  trialing: 'bg-blue-100 text-blue-800',
  cancelled: 'bg-yellow-100 text-yellow-800',
  expired: 'bg-red-100 text-red-800',
};

export function CurrentSubscription({
  subscription,
  onCancel,
  onUpgrade,
  onDowngrade,
  isCancelling,
}: CurrentSubscriptionProps) {

  return (
    <div className="rounded-lg border bg-card p-6">
      <div className="flex items-center justify-between">
        <div>
          <h3 className="text-lg font-bold text-card-foreground">
            {subscription.plan?.name ?? 'Tidak Ada Paket'}
          </h3>
          <span
            className={`mt-1 inline-block rounded-full px-2.5 py-0.5 text-xs font-medium ${statusColors[subscription.status] ?? 'bg-gray-100 text-gray-800'}`}
          >
            {subscription.status}
          </span>
        </div>
      </div>

      <div className="mt-4 grid grid-cols-2 gap-4 text-sm">
        <div>
          <p className="text-muted-foreground">Mulai</p>
          <p className="font-medium text-card-foreground">{formatDate(subscription.started_at)}</p>
        </div>
        <div>
          <p className="text-muted-foreground">Berakhir</p>
          <p className="font-medium text-card-foreground">{formatDate(subscription.expired_at)}</p>
        </div>
        <div>
          <p className="text-muted-foreground">Auto Renew</p>
          <p className="font-medium text-card-foreground">
            {subscription.auto_renew ? 'Ya' : 'Tidak'}
          </p>
        </div>
        {subscription.cancelled_at && (
          <div>
            <p className="text-muted-foreground">Dibatalkan</p>
            <p className="font-medium text-card-foreground">{formatDate(subscription.cancelled_at)}</p>
          </div>
        )}
      </div>

      {subscription.plan?.features && subscription.plan.features.length > 0 && (
        <div className="mt-4 border-t pt-4">
          <p className="mb-2 text-sm font-medium text-card-foreground">Fitur</p>
          <div className="flex flex-wrap gap-2">
            {subscription.plan.features.map((feature) => (
              <span
                key={feature.code}
                className="rounded-md bg-muted px-2 py-1 text-xs text-muted-foreground"
              >
                {feature.name}
              </span>
            ))}
          </div>
        </div>
      )}

      {subscription.status === 'active' && (
        <div className="mt-6 flex flex-wrap gap-3">
          <Button variant="outline" onClick={onUpgrade}>
            Upgrade Paket
          </Button>
          <Button variant="outline" onClick={onDowngrade}>
            Downgrade
          </Button>
          <Button
            variant="destructive"
            onClick={onCancel}
            disabled={isCancelling}
          >
            {isCancelling ? 'Membatalkan...' : 'Batalkan Langganan'}
          </Button>
        </div>
      )}
    </div>
  );
}
