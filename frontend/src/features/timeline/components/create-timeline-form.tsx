'use client';

import { useState } from 'react';
import { Plus, X } from 'lucide-react';
import { Button } from '@/components/ui/button';
import type { TimelineFormData } from '@/types';

interface CreateTimelineFormProps {
  weddingId: number | null;
  onSubmit: (data: TimelineFormData) => void;
  onCancel: () => void;
}

export function CreateTimelineForm({ weddingId, onSubmit, onCancel }: CreateTimelineFormProps) {
  const [title, setTitle] = useState('');
  const [description, setDescription] = useState('');
  const [tasks, setTasks] = useState<string[]>(['']);

  const addTask = () => setTasks([...tasks, '']);
  const removeTask = (index: number) => setTasks(tasks.filter((_, i) => i !== index));
  const updateTask = (index: number, value: string) => {
    const updated = [...tasks];
    updated[index] = value;
    setTasks(updated);
  };

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!title.trim() || !weddingId) return;

    onSubmit({
      wedding_id: weddingId,
      title: title.trim(),
      description: description.trim() || null,
      tasks: tasks.filter((t) => t.trim()).map((t, i) => ({
        title: t.trim(),
        sort_order: i,
      })),
    });
  };

  const isValid = title.trim() && weddingId;

  return (
    <form onSubmit={handleSubmit} className="rounded-xl border bg-card p-6 space-y-4">
      <h3 className="text-lg font-semibold">New Timeline</h3>

      <div>
        <label htmlFor="title" className="block text-sm font-medium mb-1">Title</label>
        <input
          id="title"
          type="text"
          value={title}
          onChange={(e) => setTitle(e.target.value)}
          className="w-full rounded-lg border bg-background px-3 py-2 text-sm"
          placeholder="e.g., Pre-Wedding Preparation"
          required
        />
      </div>

      <div>
        <label htmlFor="description" className="block text-sm font-medium mb-1">Description</label>
        <textarea
          id="description"
          value={description}
          onChange={(e) => setDescription(e.target.value)}
          className="w-full rounded-lg border bg-background px-3 py-2 text-sm"
          placeholder="Optional description"
          rows={2}
        />
      </div>

      <div>
        <div className="flex items-center justify-between mb-1">
          <label className="block text-sm font-medium">Tasks</label>
          <button type="button" onClick={addTask} className="text-sm text-primary hover:underline flex items-center gap-1">
            <Plus className="h-3 w-3" /> Add task
          </button>
        </div>
        <div className="space-y-2">
          {tasks.map((task, i) => (
            <div key={i} className="flex items-center gap-2">
              <input
                type="text"
                value={task}
                onChange={(e) => updateTask(i, e.target.value)}
                className="flex-1 rounded-lg border bg-background px-3 py-2 text-sm"
                placeholder={`Task ${i + 1}`}
              />
              {tasks.length > 1 && (
                <button type="button" onClick={() => removeTask(i)} className="text-muted-foreground hover:text-red-500">
                  <X className="h-4 w-4" />
                </button>
              )}
            </div>
          ))}
        </div>
      </div>

      <div className="flex items-center justify-end gap-3 pt-2">
        <Button type="button" variant="outline" size="sm" onClick={onCancel}>Cancel</Button>
        <Button type="submit" size="sm" disabled={!isValid}>Create Timeline</Button>
      </div>
    </form>
  );
}
