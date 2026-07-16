'use client';

import { useUser } from '@/hooks/use-auth';
import { useAuthStore } from '@/stores/auth-store';
import { useRouter } from 'next/navigation';
import { useEffect, useState } from 'react';
import { Plus, LayoutGrid, CalendarDays } from 'lucide-react';
import { Button } from '@/components/ui/button';
import { useWeddings } from '@/features/wedding/hooks/use-weddings';
import { useTimelines, useCreateTimeline, useToggleTimelineComplete, useDeleteTimeline, useCompleteTimelineTask, useUncompleteTimelineTask, useGenerateTimelineAi, useSyncGoogleCalendar } from '@/features/timeline/hooks/use-timeline';
import { TimelineCard } from '@/features/timeline/components/timeline-card';
import { CreateTimelineForm } from '@/features/timeline/components/create-timeline-form';
import { CalendarView } from '@/features/timeline/components/calendar-view';
import { ReminderSection } from '@/features/timeline/components/reminder-section';
import { AiGenerateButton } from '@/features/timeline/components/ai-generate-button';
import type { TimelineFormData } from '@/types';

export default function TimelinePage() {
  const { data: user, isLoading: isUserLoading } = useUser();
  const token = useAuthStore((s) => s.token);
  const router = useRouter();

  const [showCreateForm, setShowCreateForm] = useState(false);
  const [viewMode, setViewMode] = useState<'board' | 'calendar'>('board');

  const { data: weddingsData } = useWeddings({});
  const wedding = weddingsData?.data?.[0];
  const weddingId = wedding?.id ? Number(wedding?.id) : null;

  const { data: timelinesData, isLoading: isTimelinesLoading } = useTimelines(weddingId ? { wedding_id: weddingId } : undefined);
  const createTimeline = useCreateTimeline();
  const toggleComplete = useToggleTimelineComplete();
  const deleteTimeline = useDeleteTimeline();
  const completeTask = useCompleteTimelineTask();
  const uncompleteTask = useUncompleteTimelineTask();
  const generateAi = useGenerateTimelineAi();
  const syncCalendar = useSyncGoogleCalendar();

  const timelines = timelinesData?.data || [];

  useEffect(() => {
    if (!token && !isUserLoading) {
      router.push('/login');
    }
  }, [token, isUserLoading, router]);

  if (isUserLoading) {
    return (
      <div className="flex min-h-screen items-center justify-center">
        <p className="text-sm text-muted-foreground">Loading...</p>
      </div>
    );
  }

  if (!user) return null;

  const handleCreateTimeline = (data: TimelineFormData) => {
    createTimeline.mutate(data, {
      onSuccess: () => setShowCreateForm(false),
    });
  };

  const handleToggleTask = (timelineUuid: string, taskUuid: string, completed: boolean) => {
    if (completed) {
      completeTask.mutate({ uuid: timelineUuid, task_uuid: taskUuid });
    } else {
      uncompleteTask.mutate({ uuid: timelineUuid, task_uuid: taskUuid });
    }
  };

  return (
    <div className="min-h-screen bg-background">
      <header className="border-b bg-card">
        <div className="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
          <div className="flex items-center justify-between">
            <div>
              <h1 className="text-2xl font-bold">Timeline</h1>
              <p className="text-sm text-muted-foreground">
                Welcome, {user.name}
              </p>
            </div>
            <div className="flex items-center gap-3">
              <div className="flex items-center rounded-lg border bg-background p-0.5">
                <button
                  type="button"
                  onClick={() => setViewMode('board')}
                  className={`rounded-md px-3 py-1.5 text-sm font-medium transition-colors ${
                    viewMode === 'board' ? 'bg-primary text-primary-foreground' : 'text-muted-foreground hover:text-foreground'
                  }`}
                >
                  <LayoutGrid className="h-4 w-4 inline-block mr-1" />
                  Board
                </button>
                <button
                  type="button"
                  onClick={() => setViewMode('calendar')}
                  className={`rounded-md px-3 py-1.5 text-sm font-medium transition-colors ${
                    viewMode === 'calendar' ? 'bg-primary text-primary-foreground' : 'text-muted-foreground hover:text-foreground'
                  }`}
                >
                  <CalendarDays className="h-4 w-4 inline-block mr-1" />
                  Calendar
                </button>
              </div>
              <AiGenerateButton
                onGenerate={() => generateAi.mutate()}
                isLoading={generateAi.isPending}
              />
              <Button type="button" onClick={() => setShowCreateForm(true)} className="gap-2">
                <Plus className="h-4 w-4" />
                New Timeline
              </Button>
            </div>
          </div>
        </div>
      </header>

      <main className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        {showCreateForm && (
          <div className="mb-8">
            <CreateTimelineForm
              weddingId={weddingId}
              onSubmit={handleCreateTimeline}
              onCancel={() => setShowCreateForm(false)}
            />
          </div>
        )}

        {viewMode === 'calendar' && (
          <div className="mb-8">
            <CalendarView timelines={timelines} />
          </div>
        )}

        {viewMode === 'board' && (
          <div className="grid gap-6 lg:grid-cols-2">
            <div className="space-y-6">
              {isTimelinesLoading ? (
                <p className="text-sm text-muted-foreground">Loading timelines...</p>
              ) : timelines.length === 0 ? (
                <div className="rounded-xl border bg-card p-12 text-center">
                  <h3 className="text-lg font-semibold mb-2">No timelines yet</h3>
                  <p className="text-sm text-muted-foreground mb-4">
                    Create your first timeline to start tracking wedding preparation tasks.
                  </p>
                  <Button type="button" onClick={() => setShowCreateForm(true)} className="gap-2">
                    <Plus className="h-4 w-4" />
                    Create Timeline
                  </Button>
                </div>
              ) : (
                timelines.map((timeline) => (
                  <TimelineCard
                    key={timeline.id}
                    timeline={timeline}
                    onToggleTask={handleToggleTask}
                    onToggleComplete={(uuid) => toggleComplete.mutate(uuid)}
                    onDelete={(uuid) => deleteTimeline.mutate(uuid)}
                    onSyncCalendar={(uuid) => syncCalendar.mutate(uuid)}
                  />
                ))
              )}
            </div>
            <div>
              <ReminderSection timelines={timelines} />
            </div>
          </div>
        )}
      </main>
    </div>
  );
}
