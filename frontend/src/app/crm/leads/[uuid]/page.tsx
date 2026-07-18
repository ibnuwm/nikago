'use client';

import { useEffect } from 'react';
import { useRouter, useParams } from 'next/navigation';
import Link from 'next/link';
import { useAuthStore } from '@/stores/auth-store';
import { useUser } from '@/hooks/use-auth';
import { LeadDetail } from '@/features/crm/components/lead-detail';
import { useLead, useMoveStage, useCreateFollowUp, useDeleteLead } from '@/features/crm/hooks/use-crm';

export default function LeadDetailPage() {
  const { uuid } = useParams<{ uuid: string }>();
  const token = useAuthStore((s) => s.token);
  const router = useRouter();
  const { data: user, isLoading: isUserLoading } = useUser();

  useEffect(() => {
    if (!token && !isUserLoading) {
      router.push('/login');
    }
  }, [token, isUserLoading, router]);

  const { data: lead, isLoading } = useLead(uuid);
  const moveStage = useMoveStage();
  const addFollowUp = useCreateFollowUp();

  if (!token || !user) return null;

  const handleStageChange = (stage: string) => {
    moveStage.mutate({ uuid, stage });
  };

  const handleAddFollowUp = (data: { type: string; notes: string; follow_up_date?: string | null }) => {
    addFollowUp.mutate({ uuid, data });
  };

  return (
    <div className="min-h-screen bg-background">
      <header className="border-b bg-card">
        <div className="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
          <h1 className="text-xl font-bold text-card-foreground">Detail Lead</h1>
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
        <Link
          href="/crm"
          className="mb-6 inline-flex items-center gap-1 text-sm text-muted-foreground hover:text-foreground"
        >
          &larr; Kembali ke CRM
        </Link>

        {isLoading ? (
          <div className="py-12 text-center">
            <p className="text-sm text-muted-foreground">Memuat detail...</p>
          </div>
        ) : !lead ? (
          <div className="py-12 text-center">
            <p className="text-sm text-muted-foreground">Lead tidak ditemukan.</p>
          </div>
        ) : (
          <LeadDetail
            lead={lead}
            onStageChange={handleStageChange}
            onAddFollowUp={handleAddFollowUp}
          />
        )}
      </main>
    </div>
  );
}
