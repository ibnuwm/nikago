'use client';

import Link from 'next/link';
import type { Lead } from '@/types';

interface LeadListProps {
  leads: Lead[];
}

const stageLabels: Record<string, string> = {
  new: 'New',
  contacted: 'Contacted',
  negotiation: 'Negotiation',
  won: 'Won',
  lost: 'Lost',
};

const stageColors: Record<string, string> = {
  new: 'bg-blue-100 text-blue-800',
  contacted: 'bg-yellow-100 text-yellow-800',
  negotiation: 'bg-purple-100 text-purple-800',
  won: 'bg-green-100 text-green-800',
  lost: 'bg-red-100 text-red-800',
};

function formatCurrency(amount: number | null): string {
  if (!amount) return '-';
  return `Rp${amount.toLocaleString('id-ID')}`;
}

function formatDate(dateStr: string | null): string {
  if (!dateStr) return '-';
  const d = new Date(dateStr);
  return d.toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric' });
}

export function LeadList({ leads }: LeadListProps) {
  if (leads.length === 0) {
    return (
      <div className="py-12 text-center">
        <p className="text-sm text-muted-foreground">Belum ada lead.</p>
      </div>
    );
  }

  return (
    <div className="space-y-3">
      {leads.map((lead) => (
        <Link
          key={lead.id}
          href={`/crm/leads/${lead.id}`}
          className="block rounded-lg border bg-card p-4 transition-colors hover:bg-muted/50"
        >
          <div className="flex items-center justify-between">
            <div className="min-w-0 flex-1">
              <p className="text-sm font-medium text-card-foreground">{lead.name}</p>
              <p className="text-xs text-muted-foreground">
                {lead.email ?? lead.phone ?? '-'}
              </p>
            </div>
            <div className="text-right">
              <span
                className={`inline-block rounded-full px-2 py-0.5 text-xs font-medium ${stageColors[lead.stage] ?? 'bg-gray-100 text-gray-800'}`}
              >
                {stageLabels[lead.stage] ?? lead.stage}
              </span>
              <p className="mt-1 text-xs text-muted-foreground">
                {formatCurrency(lead.deal_value)}
              </p>
            </div>
          </div>
          <div className="mt-2 flex items-center gap-3 text-[0.7rem] text-muted-foreground">
            <span>Sumber: {lead.source ?? '-'}</span>
            <span>{formatDate(lead.created_at)}</span>
          </div>
        </Link>
      ))}
    </div>
  );
}
