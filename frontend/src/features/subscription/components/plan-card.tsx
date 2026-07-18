'use client';

import { Button } from '@/components/ui/button';
import type { SubscriptionPlan } from '@/types';

interface PlanCardProps {
  plan: SubscriptionPlan;
  isCurrentPlan?: boolean;
  onSubscribe: (planCode: string, billingPeriod: 'monthly' | 'yearly') => void;
  isPending?: boolean;
}

export function PlanCard({ plan, isCurrentPlan, onSubscribe, isPending }: PlanCardProps) {
  return (
    <div className="rounded-lg border bg-card p-6 shadow-sm">
      <h3 className="text-lg font-bold text-card-foreground">{plan.name}</h3>
      <p className="mt-1 text-sm text-muted-foreground">{plan.description}</p>

      <div className="mt-4 space-y-1">
        <div>
          <span className="text-3xl font-bold text-foreground">
            Rp{plan.monthly_price.toLocaleString('id-ID')}
          </span>
          <span className="text-sm text-muted-foreground">/bln</span>
        </div>
        {plan.yearly_price && (
          <div>
            <span className="text-lg font-semibold text-foreground">
              Rp{plan.yearly_price.toLocaleString('id-ID')}
            </span>
            <span className="text-sm text-muted-foreground">/thn</span>
          </div>
        )}
      </div>

      {plan.features.length > 0 && (
        <ul className="mt-4 space-y-2">
          {plan.features.map((feature) => (
            <li key={feature.code} className="flex items-start gap-2 text-sm text-card-foreground">
              <svg className="mt-0.5 h-4 w-4 shrink-0 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
              </svg>
              {feature.name}
            </li>
          ))}
        </ul>
      )}

      {plan.limits.length > 0 && (
        <div className="mt-4 space-y-1 border-t pt-4">
          <p className="text-xs font-medium text-muted-foreground">Batas Pemakaian</p>
          {plan.limits.map((limit) => (
            <p key={limit.feature_code} className="text-xs text-muted-foreground">
              {limit.feature_code.replace(/_/g, ' ')}: {limit.limit_value}
            </p>
          ))}
        </div>
      )}

      <div className="mt-6 flex flex-col gap-2">
        {isCurrentPlan ? (
          <Button disabled className="w-full">
            Paket Saat Ini
          </Button>
        ) : (
          <>
            <Button
              className="w-full"
              onClick={() => onSubscribe(plan.code, 'monthly')}
              disabled={isPending}
            >
              {plan.monthly_price === 0 ? 'Pilih Gratis' : 'Langganan Bulanan'}
            </Button>
            {plan.yearly_price && (
              <Button
                variant="outline"
                className="w-full"
                onClick={() => onSubscribe(plan.code, 'yearly')}
                disabled={isPending}
              >
                Langganan Tahunan
              </Button>
            )}
          </>
        )}
      </div>
    </div>
  );
}
