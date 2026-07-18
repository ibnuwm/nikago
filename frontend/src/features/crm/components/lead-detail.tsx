'use client';

import { useState } from 'react';
import type { Lead } from '@/types';

interface LeadDetailProps {
  lead: Lead;
  onStageChange: (stage: string) => void;
  onAddFollowUp: (data: { type: string; notes: string; follow_up_date?: string | null }) => void;
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

const activityTypeLabels: Record<string, string> = {
  created: 'Dibuat',
  stage_changed: 'Perubahan Stage',
  assigned: 'Ditugaskan',
  follow_up: 'Follow Up',
};

function formatDate(dateStr: string | null): string {
  if (!dateStr) return '-';
  const d = new Date(dateStr);
  return d.toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
}

function formatCurrency(amount: number | null): string {
  if (!amount) return '-';
  return `Rp${amount.toLocaleString('id-ID')}`;
}

export function LeadDetail({ lead, onStageChange, onAddFollowUp }: LeadDetailProps) {
  const [followUpType, setFollowUpType] = useState('call');
  const [followUpNotes, setFollowUpNotes] = useState('');

  const handleSubmitFollowUp = (e: React.FormEvent) => {
    e.preventDefault();
    if (!followUpNotes.trim()) return;
    onAddFollowUp({ type: followUpType, notes: followUpNotes });
    setFollowUpNotes('');
  };

  return (
    <div className="space-y-6">
      {/* Lead Info */}
      <div className="rounded-lg border bg-card p-6">
        <div className="flex items-start justify-between">
          <div>
            <h2 className="text-xl font-bold text-card-foreground">{lead.name}</h2>
            <p className="mt-1 text-sm text-muted-foreground">{lead.email ?? lead.phone ?? '-'}</p>
          </div>
          <span className={`rounded-full px-3 py-1 text-xs font-medium ${stageColors[lead.stage] ?? 'bg-gray-100 text-gray-800'}`}>
            {stageLabels[lead.stage] ?? lead.stage}
          </span>
        </div>

        <div className="mt-4 grid grid-cols-2 gap-4 text-sm">
          <div>
            <p className="text-muted-foreground">Telepon</p>
            <p className="font-medium text-card-foreground">{lead.phone ?? '-'}</p>
          </div>
          <div>
            <p className="text-muted-foreground">Sumber</p>
            <p className="font-medium text-card-foreground">{lead.source ?? '-'}</p>
          </div>
          <div>
            <p className="text-muted-foreground">Deal Value</p>
            <p className="font-medium text-card-foreground">{formatCurrency(lead.deal_value)}</p>
          </div>
          <div>
            <p className="text-muted-foreground">Ditugaskan ke</p>
            <p className="font-medium text-card-foreground">{lead.assigned_to?.name ?? '-'}</p>
          </div>
        </div>

        {lead.notes && (
          <div className="mt-4">
            <p className="text-sm text-muted-foreground">Catatan</p>
            <p className="mt-1 text-sm text-card-foreground">{lead.notes}</p>
          </div>
        )}
      </div>

      {/* Stage Actions */}
      <div className="rounded-lg border bg-card p-6">
        <h3 className="mb-3 text-sm font-semibold">Ubah Stage</h3>
        <div className="flex flex-wrap gap-2">
          {['new', 'contacted', 'negotiation', 'won', 'lost'].map((stage) => (
            <button
              key={stage}
              onClick={() => onStageChange(stage)}
              disabled={lead.stage === stage}
              className={`rounded-full px-3 py-1 text-xs font-medium transition-colors ${
                lead.stage === stage
                  ? 'bg-primary text-primary-foreground'
                  : 'border bg-background hover:bg-muted'
              }`}
            >
              {stageLabels[stage] ?? stage}
            </button>
          ))}
        </div>
      </div>

      {/* Follow Up Form */}
      <div className="rounded-lg border bg-card p-6">
        <h3 className="mb-3 text-sm font-semibold">Tambah Follow Up</h3>
        <form onSubmit={handleSubmitFollowUp} className="space-y-3">
          <select
            value={followUpType}
            onChange={(e) => setFollowUpType(e.target.value)}
            className="w-full rounded-md border bg-background px-3 py-2 text-sm"
          >
            <option value="call">Call</option>
            <option value="email">Email</option>
            <option value="whatsapp">WhatsApp</option>
            <option value="meeting">Meeting</option>
            <option value="other">Lainnya</option>
          </select>
          <textarea
            value={followUpNotes}
            onChange={(e) => setFollowUpNotes(e.target.value)}
            placeholder="Catatan follow up..."
            rows={3}
            className="w-full rounded-md border bg-background px-3 py-2 text-sm"
          />
          <button
            type="submit"
            disabled={!followUpNotes.trim()}
            className="rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground transition-colors hover:bg-primary/90 disabled:opacity-50"
          >
            Simpan
          </button>
        </form>
      </div>

      {/* Activities */}
      <div className="rounded-lg border bg-card p-6">
        <h3 className="mb-3 text-sm font-semibold">Aktivitas</h3>
        {lead.activities.length === 0 ? (
          <p className="text-sm text-muted-foreground">Belum ada aktivitas.</p>
        ) : (
          <div className="space-y-3">
            {lead.activities.map((activity) => (
              <div key={activity.id} className="border-l-2 border-muted pl-3">
                <p className="text-xs text-muted-foreground">
                  {activityTypeLabels[activity.type] ?? activity.type}
                  {' · '}
                  {formatDate(activity.created_at)}
                </p>
                <p className="mt-0.5 text-sm text-card-foreground">{activity.description}</p>
              </div>
            ))}
          </div>
        )}
      </div>

      {/* Follow Ups */}
      <div className="rounded-lg border bg-card p-6">
        <h3 className="mb-3 text-sm font-semibold">Riwayat Follow Up</h3>
        {lead.follow_ups.length === 0 ? (
          <p className="text-sm text-muted-foreground">Belum ada follow up.</p>
        ) : (
          <div className="space-y-3">
            {lead.follow_ups.map((fu) => (
              <div key={fu.id} className="rounded-md border bg-muted/30 p-3">
                <div className="flex items-center justify-between">
                  <p className="text-xs font-medium text-card-foreground">{fu.type}</p>
                  <span className={`text-xs ${fu.is_completed ? 'text-green-600' : 'text-yellow-600'}`}>
                    {fu.is_completed ? 'Selesai' : 'Pending'}
                  </span>
                </div>
                <p className="mt-1 text-sm text-muted-foreground">{fu.notes}</p>
                <p className="mt-1 text-[0.7rem] text-muted-foreground">{formatDate(fu.created_at)}</p>
              </div>
            ))}
          </div>
        )}
      </div>
    </div>
  );
}
