'use client';

import type { Checklist } from '@/types';
import { ChecklistItemRow } from './checklist-item-row';

interface ChecklistCardProps {
  checklist: Checklist;
  onToggleItem: (checklistUuid: string, itemUuid: string) => void;
  onDelete?: (uuid: string) => void;
  onDuplicate?: (uuid: string) => void;
  onEdit?: (uuid: string) => void;
}

export function ChecklistCard({ checklist, onToggleItem, onDelete, onDuplicate, onEdit }: ChecklistCardProps) {
  const progress = Math.min(checklist.progress, 100);

  return (
    <div className="rounded-lg border bg-card shadow-sm">
      <div className="flex items-center justify-between border-b px-5 py-4">
        <div className="flex-1 min-w-0">
          <h3 className="text-base font-semibold text-card-foreground">{checklist.title}</h3>
          {checklist.description && (
            <p className="mt-0.5 text-sm text-muted-foreground truncate">{checklist.description}</p>
          )}
        </div>
        <div className="flex items-center gap-2 ml-4">
          {onEdit && (
            <button
              type="button"
              onClick={() => onEdit(checklist.id)}
              className="text-xs text-muted-foreground hover:text-foreground transition-colors"
            >
              Edit
            </button>
          )}
          {onDuplicate && (
            <button
              type="button"
              onClick={() => onDuplicate(checklist.id)}
              className="text-xs text-muted-foreground hover:text-foreground transition-colors"
            >
              Duplicate
            </button>
          )}
          {onDelete && (
            <button
              type="button"
              onClick={() => onDelete(checklist.id)}
              className="text-xs text-red-500 hover:text-red-700 transition-colors"
            >
              Delete
            </button>
          )}
        </div>
      </div>

      <div className="px-5 pt-3 pb-1">
        <div className="flex items-center justify-between text-xs text-muted-foreground">
          <span>Progress</span>
          <span>{progress}%</span>
        </div>
        <div className="mt-1 h-1.5 overflow-hidden rounded-full bg-muted">
          <div
            className="h-full rounded-full bg-primary transition-all"
            style={{ width: `${progress}%` }}
          />
        </div>
      </div>

      {checklist.items && checklist.items.length > 0 && (
        <div className="space-y-2 px-5 py-3">
          {checklist.items.map((item) => (
            <ChecklistItemRow
              key={item.id}
              item={item}
              onToggle={(itemUuid) => onToggleItem(checklist.id, itemUuid)}
            />
          ))}
        </div>
      )}
    </div>
  );
}
