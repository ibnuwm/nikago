'use client';

import type { ChecklistItem } from '@/types';
import { cn } from '@/lib/utils';

interface ChecklistItemRowProps {
  item: ChecklistItem;
  onToggle: (itemUuid: string) => void;
  onDelete?: (itemUuid: string) => void;
}

const priorityColors: Record<string, string> = {
  low: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
  medium: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
  high: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
};

export function ChecklistItemRow({ item, onToggle, onDelete }: ChecklistItemRowProps) {
  const isCompleted = !!item.completed_at;

  return (
    <div
      className={cn(
        'flex items-center gap-3 rounded-lg border bg-card px-4 py-3 shadow-sm transition-all',
        isCompleted && 'opacity-60',
      )}
    >
      <button
        type="button"
        onClick={() => onToggle(item.id)}
        className={cn(
          'flex size-5 shrink-0 items-center justify-center rounded-full border-2 transition-colors',
          isCompleted
            ? 'border-primary bg-primary text-primary-foreground'
            : 'border-muted-foreground hover:border-primary',
        )}
      >
        {isCompleted && (
          <svg className="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={3}>
            <path strokeLinecap="round" strokeLinejoin="round" d="M5 13l4 4L19 7" />
          </svg>
        )}
      </button>

      <div className="flex-1 min-w-0">
        <p className={cn('text-sm font-medium text-card-foreground', isCompleted && 'line-through')}>
          {item.title}
        </p>
        {item.due_date && (
          <p className="mt-0.5 text-xs text-muted-foreground">Due: {item.due_date}</p>
        )}
      </div>

      <span className={cn('rounded-full px-2.5 py-0.5 text-xs font-medium', priorityColors[item.priority])}>
        {item.priority}
      </span>
    </div>
  );
}
