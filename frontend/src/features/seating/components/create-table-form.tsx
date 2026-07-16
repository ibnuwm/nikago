'use client';

import { useState } from 'react';

interface CreateTableFormProps {
  weddingId: number;
  onSubmit: (name: string, capacity: number, shape: string) => void;
  onCancel: () => void;
}

export function CreateTableForm({ weddingId, onSubmit, onCancel }: CreateTableFormProps) {
  const [name, setName] = useState('');
  const [capacity, setCapacity] = useState('8');
  const [shape, setShape] = useState('round');

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!name.trim()) return;
    onSubmit(name.trim(), Number(capacity), shape);
  };

  return (
    <form onSubmit={handleSubmit} className="rounded-lg border bg-card p-5 shadow-sm">
      <h3 className="text-base font-semibold text-card-foreground">New Table</h3>
      <div className="mt-3 space-y-3">
        <div>
          <label htmlFor="name" className="text-sm font-medium text-foreground">Table Name</label>
          <input
            id="name"
            type="text"
            value={name}
            onChange={(e) => setName(e.target.value)}
            placeholder="e.g. Table 1, VIP Table"
            className="mt-1 block w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring"
            required
          />
        </div>
        <div className="grid grid-cols-2 gap-3">
          <div>
            <label htmlFor="capacity" className="text-sm font-medium text-foreground">Capacity</label>
            <input
              id="capacity"
              type="number"
              value={capacity}
              onChange={(e) => setCapacity(e.target.value)}
              min={1}
              max={100}
              className="mt-1 block w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-ring"
            />
          </div>
          <div>
            <label htmlFor="shape" className="text-sm font-medium text-foreground">Shape</label>
            <select
              id="shape"
              value={shape}
              onChange={(e) => setShape(e.target.value)}
              className="mt-1 block w-full rounded-md border border-input bg-background px-3 py-2 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-ring"
            >
              <option value="round">Round</option>
              <option value="rectangle">Rectangle</option>
              <option value="square">Square</option>
            </select>
          </div>
        </div>
      </div>
      <div className="mt-4 flex items-center gap-2">
        <button
          type="submit"
          className="rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/80 transition-colors"
        >
          Create
        </button>
        <button
          type="button"
          onClick={onCancel}
          className="rounded-md border border-input bg-background px-4 py-2 text-sm font-medium text-foreground hover:bg-muted transition-colors"
        >
          Cancel
        </button>
      </div>
    </form>
  );
}
