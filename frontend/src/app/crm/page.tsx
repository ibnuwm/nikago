'use client';

import { useState, useEffect } from 'react';
import { useRouter } from 'next/navigation';
import { useAuthStore } from '@/stores/auth-store';
import { useUser } from '@/hooks/use-auth';
import { LeadList } from '@/features/crm/components/lead-list';
import { PipelineBoard } from '@/features/crm/components/pipeline-board';
import { useLeads, usePipelines } from '@/features/crm/hooks/use-crm';

export default function CrmPage() {
  const [search, setSearch] = useState('');
  const [stageFilter, setStageFilter] = useState('');
  const token = useAuthStore((s) => s.token);
  const router = useRouter();
  const { data: user, isLoading: isUserLoading } = useUser();

  useEffect(() => {
    if (!token && !isUserLoading) {
      router.push('/login');
    }
  }, [token, isUserLoading, router]);

  const { data: leadsData, isLoading: isLeadsLoading } = useLeads({
    search: search || undefined,
    stage: stageFilter || undefined,
  });

  const { data: pipelines } = usePipelines();

  if (!token || !user) return null;

  const leads = leadsData?.data ?? [];

  return (
    <div className="min-h-screen bg-background">
      <header className="border-b bg-card">
        <div className="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
          <h1 className="text-xl font-bold text-card-foreground">CRM</h1>
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
        <div className="mb-6 flex gap-3">
          <a
            href="/crm"
            className="rounded-md border bg-background px-3 py-1.5 text-xs font-medium transition-colors hover:bg-muted"
          >
            Leads
          </a>
          <a
            href="/crm/statistics"
            className="rounded-md border bg-background px-3 py-1.5 text-xs font-medium transition-colors hover:bg-muted"
          >
            Statistics
          </a>
        </div>

        {/* Pipeline Board */}
        {pipelines && (
          <div className="mb-8">
            <h2 className="mb-4 text-lg font-semibold">Pipeline</h2>
            <PipelineBoard pipelines={pipelines} />
          </div>
        )}

        {/* Search & Filter */}
        <div className="mb-6 flex flex-wrap gap-3">
          <input
            type="text"
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            placeholder="Cari nama, email, atau telepon..."
            className="flex-1 rounded-md border bg-background px-3 py-2 text-sm"
          />
          <select
            value={stageFilter}
            onChange={(e) => setStageFilter(e.target.value)}
            className="rounded-md border bg-background px-3 py-2 text-sm"
          >
            <option value="">Semua Stage</option>
            <option value="new">New</option>
            <option value="contacted">Contacted</option>
            <option value="negotiation">Negotiation</option>
            <option value="won">Won</option>
            <option value="lost">Lost</option>
          </select>
        </div>

        {isLeadsLoading ? (
          <div className="py-12 text-center">
            <p className="text-sm text-muted-foreground">Memuat lead...</p>
          </div>
        ) : (
          <LeadList leads={leads} />
        )}
      </main>
    </div>
  );
}
