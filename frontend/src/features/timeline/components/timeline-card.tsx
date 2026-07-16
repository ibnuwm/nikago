'use client';

import { CheckCircle2, Clock, MoreHorizontal, Trash2, CalendarSync } from 'lucide-react';
import type { Timeline } from '@/types';
import { TimelineTaskRow } from './timeline-task-row';

interface TimelineCardProps {
  timeline: Timeline;
  onToggleTask: (timelineUuid: string, taskUuid: string, completed: boolean) => void;
  onToggleComplete: (uuid: string) => void;
  onDelete: (uuid: string) => void;
  onSyncCalendar: (uuid: string) => void;
}

export function TimelineCard({ timeline, onToggleTask, onToggleComplete, onDelete, onSyncCalendar }: TimelineCardProps) {
  return (
    <div className="rounded-xl border bg-card shadow-sm">
      <div className="flex items-center justify-between border-b px-6 py-4">
        <div className="flex-1 min-w-0">
          <h3 className="text-lg font-semibold">{timeline.title}</h3>
          {timeline.description && (
            <p className="text-sm text-muted-foreground mt-0.5">{timeline.description}</p>
          )}
        </div>
        <div className="flex items-center gap-2 ml-4">
          {timeline.completed_at ? (
            <span className="flex items-center gap-1 text-xs text-green-600 font-medium">
              <CheckCircle2 className="h-4 w-4" />
              Completed
            </span>
          ) : (
            <span className="flex items-center gap-1 text-xs text-muted-foreground">
              <Clock className="h-4 w-4" />
              In Progress
            </span>
          )}
          <div className="flex items-center gap-1">
            <button
              type="button"
              onClick={() => onSyncCalendar(timeline.id)}
              className="rounded p-1.5 text-muted-foreground hover:text-foreground hover:bg-accent"
              title="Sync with Google Calendar"
            >
              <CalendarSync className="h-4 w-4" />
            </button>
            <button
              type="button"
              onClick={() => onToggleComplete(timeline.id)}
              className="rounded p-1.5 text-muted-foreground hover:text-foreground hover:bg-accent"
              title={timeline.completed_at ? 'Mark as incomplete' : 'Mark as complete'}
            >
              <CheckCircle2 className={`h-4 w-4 ${timeline.completed_at ? 'text-green-500' : ''}`} />
            </button>
            <button
              type="button"
              onClick={() => onDelete(timeline.id)}
              className="rounded p-1.5 text-muted-foreground hover:text-red-500 hover:bg-accent"
              title="Delete timeline"
            >
              <Trash2 className="h-4 w-4" />
            </button>
            <button
              type="button"
              className="rounded p-1.5 text-muted-foreground hover:text-foreground hover:bg-accent"
            >
              <MoreHorizontal className="h-4 w-4" />
            </button>
          </div>
        </div>
      </div>

      <div className="px-6 py-3">
        <div className="flex items-center gap-3">
          <div className="flex-1 h-2 bg-muted rounded-full overflow-hidden">
            <div
              className="h-full bg-primary rounded-full transition-all duration-300"
              style={{ width: `${timeline.progress}%` }}
            />
          </div>
          <span className="text-sm font-medium text-muted-foreground shrink-0">
            {Math.round(timeline.progress)}%
          </span>
        </div>
      </div>

      {timeline.tasks && timeline.tasks.length > 0 && (
        <div className="px-6 pb-4 space-y-2">
          {timeline.tasks.map((task) => (
            <TimelineTaskRow
              key={task.id}
              task={task}
              onToggle={(taskUuid, completed) => onToggleTask(timeline.id, taskUuid, completed)}
            />
          ))}
        </div>
      )}

      {(!timeline.tasks || timeline.tasks.length === 0) && (
        <div className="px-6 pb-4">
          <p className="text-sm text-muted-foreground text-center py-4">No tasks yet.</p>
        </div>
      )}
    </div>
  );
}
