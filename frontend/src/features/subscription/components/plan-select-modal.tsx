'use client';

import { useState } from 'react';
import { Button } from '@/components/ui/button';
import type { SubscriptionPlan } from '@/types';

interface PlanSelectModalProps {
  plans: SubscriptionPlan[];
  title: string;
  currentPlanId?: number;
  onSelect: (planCode: string) => void;
  onClose: () => void;
  isPending?: boolean;
}

export function PlanSelectModal({
  plans,
  title,
  currentPlanId,
  onSelect,
  onClose,
  isPending,
}: PlanSelectModalProps) {
  const [selectedCode, setSelectedCode] = useState<string | null>(null);

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
      <div className="mx-4 w-full max-w-md rounded-lg bg-card p-6 shadow-lg">
        <h3 className="text-lg font-bold text-card-foreground">{title}</h3>
        <p className="mt-1 text-sm text-muted-foreground">Pilih paket tujuan:</p>

        <div className="mt-4 space-y-2">
          {plans
            .filter((p) => p.id !== currentPlanId)
            .map((plan) => (
              <button
                key={plan.code}
                onClick={() => setSelectedCode(plan.code)}
                className={`w-full rounded-lg border p-3 text-left transition-colors ${
                  selectedCode === plan.code
                    ? 'border-primary bg-primary/5'
                    : 'border-border hover:bg-muted'
                }`}
              >
                <p className="font-medium text-card-foreground">{plan.name}</p>
                <p className="text-xs text-muted-foreground">
                  Rp{plan.monthly_price.toLocaleString('id-ID')}/bln
                </p>
              </button>
            ))}
        </div>

        <div className="mt-6 flex gap-3">
          <Button variant="outline" className="flex-1" onClick={onClose}>
            Batal
          </Button>
          <Button
            className="flex-1"
            disabled={!selectedCode || isPending}
            onClick={() => selectedCode && onSelect(selectedCode)}
          >
            {isPending ? 'Memproses...' : 'Konfirmasi'}
          </Button>
        </div>
      </div>
    </div>
  );
}
