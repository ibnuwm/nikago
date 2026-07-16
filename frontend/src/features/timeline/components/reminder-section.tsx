'use client';

import { Bell, Clock } from 'lucide-react';
import type { Timeline } from '@/types';

interface ReminderSectionProps {
  timelines: Timeline[];
}

export function ReminderSection({ timelines }: ReminderSectionProps) {
  const today = new Date().toISOString().split('T')[0];
  const upcomingTasks = timelines.flatMap((tl) =>
    (tl.tasks || [])
      .filter((t) => !t.completed_at && t.due_date && t.due_date >= today)
      .map((t) => ({ ...t, timelineTitle: tl.title }))
  )
    .sort((a, b) => (a.due_date || '').localeCompare(b.due_date || ''));

  const overdueTasks = timelines.flatMap((tl) =>
    (tl.tasks || [])
      .filter((t) => !t.completed_at && t.due_date && t.due_date < today)
      .map((t) => ({ ...t, timelineTitle: tl.title }))
  );

  return (
    <div className="rounded-xl border bg-card">
      <div className="border-b px-6 py-4">
        <h3 className="text-lg font-semibold flex items-center gap-2">
          <Bell className="h-5 w-5" />
          Reminders
        </h3>
      </div>
      <div className="p-4 space-y-4">
        {overdueTasks.length > 0 && (
          <div>
            <h4 className="text-sm font-medium text-red-600 mb-2 flex items-center gap-1">
              <Clock className="h-4 w-4" />
              Overdue ({overdueTasks.length})
            </h4>
            <div className="space-y-2">
              {overdueTasks.map((task, i) => (
                <div key={i} className="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm">
                  <p className="font-medium">{task.title}</p>
                  <p className="text-xs text-muted-foreground">{task.timelineTitle} &middot; Due {task.due_date}</p>
                </div>
              ))}
            </div>
          </div>
        )}

        {upcomingTasks.length > 0 && (
          <div>
            <h4 className="text-sm font-medium mb-2">Upcoming ({upcomingTasks.length})</h4>
            <div className="space-y-2">
              {upcomingTasks.slice(0, 5).map((task, i) => (
                <div key={i} className="rounded-lg border bg-card px-3 py-2 text-sm">
                  <p className="font-medium">{task.title}</p>
                  <p className="text-xs text-muted-foreground">{task.timelineTitle} &middot; Due {task.due_date}</p>
                </div>
              ))}
            </div>
          </div>
        )}

        {overdueTasks.length === 0 && upcomingTasks.length === 0 && (
          <p className="text-sm text-muted-foreground text-center py-4">No reminders.</p>
        )}
      </div>
    </div>
  );
}
