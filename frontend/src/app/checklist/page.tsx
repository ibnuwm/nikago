'use client';

import { useUser } from '@/hooks/use-auth';
import { useWeddings } from '@/features/wedding/hooks/use-weddings';
import {
  useChecklists,
  useCreateChecklist,
  useDeleteChecklist,
  useDuplicateChecklist,
  useCompleteChecklistItem,
  useUncompleteChecklistItem,
  useGenerateChecklistAi,
} from '@/features/checklist/hooks/use-checklist';
import { ChecklistCard } from '@/features/checklist/components/checklist-card';
import { CreateChecklistForm } from '@/features/checklist/components/create-checklist-form';
import { useAuthStore } from '@/stores/auth-store';
import { useRouter } from 'next/navigation';
import { useEffect, useState } from 'react';

export default function ChecklistPage() {
  const { data: user, isLoading: isUserLoading } = useUser();
  const { data: weddingsData, isLoading: isWeddingsLoading } = useWeddings();
  const { data: checklistsData, isLoading: isChecklistsLoading } = useChecklists();
  const token = useAuthStore((s) => s.token);
  const router = useRouter();
  const [showCreate, setShowCreate] = useState(false);

  const weddingId = weddingsData?.data?.[0]?.id;

  const createChecklist = useCreateChecklist();
  const deleteChecklist = useDeleteChecklist();
  const duplicateChecklist = useDuplicateChecklist();
  const completeItem = useCompleteChecklistItem();
  const uncompleteItem = useUncompleteChecklistItem();
  const generateAi = useGenerateChecklistAi();

  const checklists = checklistsData?.data ?? [];

  useEffect(() => {
    if (!token && !isUserLoading) {
      router.push('/login');
    }
  }, [token, isUserLoading, router]);

  if (isUserLoading || isWeddingsLoading || isChecklistsLoading) {
    return (
      <div className="flex min-h-screen items-center justify-center">
        <p className="text-sm text-muted-foreground">Loading...</p>
      </div>
    );
  }

  if (!user) {
    return null;
  }

  const handleCreate = (title: string, description?: string) => {
    if (!weddingId) return;
    createChecklist.mutate(
      { wedding_id: Number(weddingId), title, description },
      { onSuccess: () => setShowCreate(false) },
    );
  };

  const handleToggleItem = (checklistUuid: string, itemUuid: string) => {
    const checklist = checklists.find((c) => c.id === checklistUuid);
    const item = checklist?.items?.find((i) => i.id === itemUuid);
    if (item?.completed_at) {
      uncompleteItem.mutate({ checklistUuid, itemUuid });
    } else {
      completeItem.mutate({ checklistUuid, itemUuid });
    }
  };

  return (
    <div className="min-h-screen bg-background">
      <header className="border-b bg-card">
        <div className="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
          <h1 className="text-xl font-bold text-card-foreground">Checklists</h1>
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
            <h2 className="text-2xl font-bold text-foreground">Wedding Checklists</h2>
            <p className="mt-1 text-sm text-muted-foreground">
              Track your wedding preparation tasks.
            </p>
          </div>
          <div className="flex items-center gap-2">
            <button
              type="button"
              onClick={() => generateAi.mutate()}
              disabled={generateAi.isPending}
              className="inline-flex h-7 shrink-0 items-center justify-center gap-1 rounded-[min(var(--radius-md),12px)] border border-border bg-background px-2.5 text-[0.8rem] font-medium whitespace-nowrap text-foreground transition-all outline-none select-none hover:bg-muted hover:text-foreground disabled:opacity-50"
            >
              {generateAi.isPending ? 'Generating...' : 'AI Generate'}
            </button>
            <button
              type="button"
              onClick={() => setShowCreate(true)}
              className="inline-flex h-7 shrink-0 items-center justify-center gap-1 rounded-[min(var(--radius-md),12px)] bg-primary px-2.5 text-[0.8rem] font-medium whitespace-nowrap text-primary-foreground transition-all outline-none select-none hover:bg-primary/80"
            >
              Add Checklist
            </button>
          </div>
        </div>

        <div className="mt-8 space-y-6">
          {showCreate && (
            <CreateChecklistForm
              weddingId={1}
              onSubmit={handleCreate}
              onCancel={() => setShowCreate(false)}
            />
          )}

          {checklists.length > 0 ? (
            checklists.map((checklist) => (
              <ChecklistCard
                key={checklist.id}
                checklist={checklist}
                onToggleItem={handleToggleItem}
                onDelete={(uuid) => deleteChecklist.mutate(uuid)}
                onDuplicate={(uuid) => duplicateChecklist.mutate(uuid)}
              />
            ))
          ) : (
            <div className="rounded-lg border bg-card p-12 text-center shadow-sm">
              <p className="text-muted-foreground">
                No checklists yet. Create your first checklist or use AI to generate one.
              </p>
            </div>
          )}
        </div>
      </main>
    </div>
  );
}
