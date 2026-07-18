'use client';

import { useState, useEffect } from 'react';
import { useRouter } from 'next/navigation';
import { useAuthStore } from '@/stores/auth-store';
import { useUser } from '@/hooks/use-auth';
import { PlanCard } from '@/features/subscription/components/plan-card';
import { CurrentSubscription } from '@/features/subscription/components/current-subscription';
import { SubscriptionHistory } from '@/features/subscription/components/subscription-history';
import { PlanSelectModal } from '@/features/subscription/components/plan-select-modal';
import {
  usePlans,
  useCurrentSubscription,
  useSubscribe,
  useUpgradeSubscription,
  useDowngradeSubscription,
  useCancelSubscription,
  useSubscriptionHistory,
} from '@/features/subscription/hooks/use-subscription';

type Tab = 'plans' | 'current' | 'history';
type ModalMode = 'upgrade' | 'downgrade' | null;

export default function SubscriptionsPage() {
  const [activeTab, setActiveTab] = useState<Tab>('current');
  const [modalMode, setModalMode] = useState<ModalMode>(null);
  const token = useAuthStore((s) => s.token);
  const router = useRouter();
  const { data: user, isLoading: isUserLoading } = useUser();

  useEffect(() => {
    if (!token && !isUserLoading) {
      router.push('/login');
    }
  }, [token, isUserLoading, router]);

  const { data: plans, isLoading: isPlansLoading } = usePlans();
  const { data: subscription, isLoading: isSubLoading } = useCurrentSubscription();
  const { data: histories, isLoading: isHistoryLoading } = useSubscriptionHistory();

  const subscribe = useSubscribe();
  const upgrade = useUpgradeSubscription();
  const downgrade = useDowngradeSubscription();
  const cancel = useCancelSubscription();

  const handleSubscribe = (planCode: string, billingPeriod: 'monthly' | 'yearly') => {
    subscribe.mutate({ plan_code: planCode, billing_period: billingPeriod });
  };

  const handleUpgrade = (planCode: string) => {
    upgrade.mutate({ plan_code: planCode });
    setModalMode(null);
  };

  const handleDowngrade = (planCode: string) => {
    downgrade.mutate({ plan_code: planCode });
    setModalMode(null);
  };

  const handleCancel = () => {
    if (confirm('Apakah Anda yakin ingin membatalkan langganan?')) {
      cancel.mutate(undefined);
    }
  };

  if (!token || !user) return null;

  const tabs: { key: Tab; label: string }[] = [
    { key: 'current', label: 'Langganan Aktif' },
    { key: 'plans', label: 'Semua Paket' },
    { key: 'history', label: 'Riwayat' },
  ];

  return (
    <div className="min-h-screen bg-background">
      <header className="border-b bg-card">
        <div className="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
          <h1 className="text-xl font-bold text-card-foreground">Langganan</h1>
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
        <div className="mb-6 flex gap-1 rounded-lg bg-muted p-1">
          {tabs.map((tab) => (
            <button
              key={tab.key}
              onClick={() => setActiveTab(tab.key)}
              className={`flex-1 rounded-md px-4 py-2 text-sm font-medium transition-colors ${
                activeTab === tab.key
                  ? 'bg-background text-foreground shadow-sm'
                  : 'text-muted-foreground hover:text-foreground'
              }`}
            >
              {tab.label}
            </button>
          ))}
        </div>

        {activeTab === 'plans' && (
          <div>
            {isPlansLoading ? (
              <div className="py-12 text-center">
                <p className="text-sm text-muted-foreground">Memuat paket...</p>
              </div>
            ) : !plans || plans.length === 0 ? (
              <div className="py-12 text-center">
                <p className="text-sm text-muted-foreground">Belum ada paket tersedia.</p>
              </div>
            ) : (
              <div className="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                {plans.map((plan) => (
                  <PlanCard
                    key={plan.code}
                    plan={plan}
                    isCurrentPlan={subscription?.plan_id === plan.id}
                    onSubscribe={handleSubscribe}
                    isPending={subscribe.isPending}
                  />
                ))}
              </div>
            )}
          </div>
        )}

        {activeTab === 'current' && (
          <div>
            {isSubLoading ? (
              <div className="py-12 text-center">
                <p className="text-sm text-muted-foreground">Memuat langganan...</p>
              </div>
            ) : !subscription ? (
              <div className="py-12 text-center">
                <p className="text-sm text-muted-foreground">
                  Belum ada langganan aktif. Silakan pilih paket.
                </p>
              </div>
            ) : (
              <CurrentSubscription
                subscription={subscription}
                onCancel={handleCancel}
                onUpgrade={() => setModalMode('upgrade')}
                onDowngrade={() => setModalMode('downgrade')}
                isCancelling={cancel.isPending}
              />
            )}
          </div>
        )}

        {activeTab === 'history' && (
          <div>
            {isHistoryLoading ? (
              <div className="py-12 text-center">
                <p className="text-sm text-muted-foreground">Memuat riwayat...</p>
              </div>
            ) : (
              <SubscriptionHistory histories={histories ?? []} />
            )}
          </div>
        )}
      </main>

      {modalMode && plans && subscription && (
        <PlanSelectModal
          plans={plans}
          title={modalMode === 'upgrade' ? 'Upgrade Paket' : 'Downgrade Paket'}
          currentPlanId={subscription.plan_id}
          onSelect={modalMode === 'upgrade' ? handleUpgrade : handleDowngrade}
          onClose={() => setModalMode(null)}
          isPending={upgrade.isPending || downgrade.isPending}
        />
      )}
    </div>
  );
}
