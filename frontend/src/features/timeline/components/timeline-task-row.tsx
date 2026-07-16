'use client';

import { Circle, CheckCircle2, GripVertical } from 'lucide-react';
import type { TimelineTask } from '@/types';

interface TimelineTaskRowProps {
  task: TimelineTask;
  onToggle: (taskUuid: string, completed: boolean) => void;
}

export function TimelineTaskRow({ task, onToggle }: TimelineTaskRowProps) {
  return (
    <div className="flex items-center gap-3 rounded-lg border bg-card px-4 py-3">
      <button
        type="button"
        className="shrink-0 text-muted-foreground hover:text-foreground cursor-grab"
        aria-label="Drag to reorder"
      >
        <GripVertical className="h-4 w-4" />
      </button>
      <button
        type="button"
        onClick={() => onToggle(task.id, !task.completed_at)}
        className="shrink-0"
      >
        {task.completed_at ? (
          <CheckCircle2 className="h-5 w-5 text-green-500" />
        ) : (
          <Circle className="h-5 w-5 text-muted-foreground hover:text-foreground" />
        )}
      </button>
      <div className="flex-1 min-w-0">
        <p className={`text-sm font-medium ${task.completed_at ? 'line-through text-muted-foreground' : ''}`}>
          {task.title}
        </p>
        {task.due_date && (
          <p className="text-xs text-muted-foreground">Due: {task.due_date}</p>
        )}
      </div>
      {task.priority && (
        <span className={`text-xs font-medium px-2 py-0.5 rounded-full ${
          task.priority === 'high' ? 'bg-red-100 text-red-700' :
          task.priority === 'medium' ? 'bg-yellow-100 text-yellow-700' :
          'bg-green-100 text-green-700'
        }`}>
          {task.priority}
        </span>
      )}
    </div>
  );
}
