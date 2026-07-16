'use client';

import { useUser } from '@/hooks/use-auth';
import { useWeddings } from '@/features/wedding/hooks/use-weddings';
import { useGuests } from '@/features/guest/hooks/use-guests';
import {
  useSeatingTables,
  useCreateSeatingTable,
  useDeleteSeatingTable,
  useAssignGuest,
  useUnassignGuest,
  useAutoGenerateSeating,
} from '@/features/seating/hooks/use-seating';
import { SeatingTableCard } from '@/features/seating/components/seating-table-card';
import { CreateTableForm } from '@/features/seating/components/create-table-form';
import { useAuthStore } from '@/stores/auth-store';
import { useRouter } from 'next/navigation';
import { useEffect, useState } from 'react';
import type { SeatAssignmentFormData } from '@/types';

export default function SeatingPage() {
  const { data: user, isLoading: isUserLoading } = useUser();
  const { data: weddingsData, isLoading: isWeddingsLoading } = useWeddings();
  const { data: seatingData, isLoading: isSeatingLoading } = useSeatingTables();
  const { data: guestsData } = useGuests();
  const token = useAuthStore((s) => s.token);
  const router = useRouter();
  const [showCreate, setShowCreate] = useState(false);

  const weddingId = weddingsData?.data?.[0]?.id;
  const guests = (guestsData?.data ?? []).map((g) => ({ id: g.id, name: g.name }));

  const createTable = useCreateSeatingTable();
  const deleteTable = useDeleteSeatingTable();
  const assignGuest = useAssignGuest();
  const unassignGuest = useUnassignGuest();
  const autoGenerate = useAutoGenerateSeating();

  const tables = seatingData?.data ?? [];

  useEffect(() => {
    if (!token && !isUserLoading) {
      router.push('/login');
    }
  }, [token, isUserLoading, router]);

  if (isUserLoading || isWeddingsLoading || isSeatingLoading) {
    return (
      <div className="flex min-h-screen items-center justify-center">
        <p className="text-sm text-muted-foreground">Loading...</p>
      </div>
    );
  }

  if (!user) {
    return null;
  }

  const handleCreate = (name: string, capacity: number, shape: string) => {
    if (!weddingId) return;
    createTable.mutate(
      { wedding_id: Number(weddingId), name, capacity, shape },
      { onSuccess: () => setShowCreate(false) },
    );
  };

  return (
    <div className="min-h-screen bg-background">
      <header className="border-b bg-card">
        <div className="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
          <h1 className="text-xl font-bold text-card-foreground">Guest Seating</h1>
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
        <div className="flex items-center justify-between">
          <div>
            <h2 className="text-2xl font-bold text-foreground">Seating Arrangement</h2>
            <p className="mt-1 text-sm text-muted-foreground">
              Manage tables and assign guests to seats.
            </p>
          </div>
          <div className="flex items-center gap-2">
            <button
              type="button"
              onClick={() => autoGenerate.mutate(weddingId ? Number(weddingId) : undefined)}
              disabled={autoGenerate.isPending}
              className="inline-flex h-7 shrink-0 items-center justify-center gap-1 rounded-[min(var(--radius-md),12px)] border border-border bg-background px-2.5 text-[0.8rem] font-medium whitespace-nowrap text-foreground transition-all outline-none select-none hover:bg-muted hover:text-foreground disabled:opacity-50"
            >
              {autoGenerate.isPending ? 'Generating...' : 'Auto Generate'}
            </button>
            <button
              type="button"
              onClick={() => setShowCreate(true)}
              className="inline-flex h-7 shrink-0 items-center justify-center gap-1 rounded-[min(var(--radius-md),12px)] bg-primary px-2.5 text-[0.8rem] font-medium whitespace-nowrap text-primary-foreground transition-all outline-none select-none hover:bg-primary/80"
            >
              Add Table
            </button>
          </div>
        </div>

        {autoGenerate.data && (
          <div className="mt-4 rounded-md border bg-muted/50 px-4 py-3 text-sm text-foreground">
            {autoGenerate.data.message}
          </div>
        )}

        <div className="mt-8 space-y-6">
          {showCreate && weddingId && (
            <CreateTableForm
              weddingId={Number(weddingId)}
              onSubmit={handleCreate}
              onCancel={() => setShowCreate(false)}
            />
          )}

          {tables.length > 0 ? (
            <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
              {tables.map((table) => (
                <SeatingTableCard
                  key={table.id}
                  table={table}
                  guests={guests}
                  onAssign={(tableUuid, data: SeatAssignmentFormData) =>
                    assignGuest.mutate({ tableUuid, data })
                  }
                  onUnassign={(tableUuid, assignmentUuid) =>
                    unassignGuest.mutate({ tableUuid, assignmentUuid })
                  }
                  onDelete={(uuid) => deleteTable.mutate(uuid)}
                />
              ))}
            </div>
          ) : (
            <div className="rounded-lg border bg-card p-12 text-center shadow-sm">
              <p className="text-muted-foreground">
                No tables yet. Create your first table or use auto-generate.
              </p>
            </div>
          )}
        </div>
      </main>
    </div>
  );
}
