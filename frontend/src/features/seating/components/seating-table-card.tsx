'use client';

import { useState } from 'react';
import type { SeatingTable, SeatedGuest, SeatAssignmentFormData } from '@/types';

interface SeatingTableCardProps {
  table: SeatingTable;
  guests: { id: string; name: string }[];
  onAssign: (tableUuid: string, data: SeatAssignmentFormData) => void;
  onUnassign: (tableUuid: string, assignmentUuid: string) => void;
  onEdit?: (uuid: string) => void;
  onDelete?: (uuid: string) => void;
}

export function SeatingTableCard({ table, guests, onAssign, onUnassign, onEdit, onDelete }: SeatingTableCardProps) {
  const [showAssign, setShowAssign] = useState(false);
  const [selectedGuestId, setSelectedGuestId] = useState('');
  const [seatNumber, setSeatNumber] = useState('');

  const isEmpty = table.assigned_count === 0;
  const isFull = table.assigned_count >= table.capacity;
  const availableSeats = table.capacity - table.assigned_count;

  const handleAssign = (e: React.FormEvent) => {
    e.preventDefault();
    if (!selectedGuestId) return;
    onAssign(table.id, {
      guest_id: selectedGuestId,
      seat_number: seatNumber ? Number(seatNumber) : undefined,
    });
    setSelectedGuestId('');
    setSeatNumber('');
    setShowAssign(false);
  };

  const shapeIcon = table.shape === 'round' ? '●' : table.shape === 'rectangle' ? '▬' : '■';

  return (
    <div className="rounded-lg border bg-card shadow-sm">
      <div className="flex items-center justify-between border-b px-5 py-4">
        <div className="flex items-center gap-2 min-w-0">
          <span className="text-lg text-muted-foreground">{shapeIcon}</span>
          <div>
            <h3 className="text-base font-semibold text-card-foreground">{table.name}</h3>
            <p className="text-xs text-muted-foreground">
              {table.assigned_count}/{table.capacity} seats filled
            </p>
          </div>
        </div>
        <div className="flex items-center gap-2 ml-4">
          {onEdit && (
            <button
              type="button"
              onClick={() => onEdit(table.id)}
              className="text-xs text-muted-foreground hover:text-foreground transition-colors"
            >
              Edit
            </button>
          )}
          {onDelete && (
            <button
              type="button"
              onClick={() => onDelete(table.id)}
              className="text-xs text-red-500 hover:text-red-700 transition-colors"
            >
              Delete
            </button>
          )}
        </div>
      </div>

      <div className="px-5 pt-3 pb-1">
        <div className="h-1.5 overflow-hidden rounded-full bg-muted">
          <div
            className="h-full rounded-full bg-primary transition-all"
            style={{ width: `${(table.assigned_count / Math.max(table.capacity, 1)) * 100}%` }}
          />
        </div>
      </div>

      {!isEmpty && table.guests && (
        <div className="space-y-1 px-5 py-3">
          {table.guests.map((guest) => (
            <div key={guest.id} className="flex items-center justify-between rounded-md bg-muted/50 px-3 py-1.5">
              <div className="flex items-center gap-2">
                <span className="text-xs text-muted-foreground">
                  {guest.seat_number ? `#${guest.seat_number}` : '—'}
                </span>
                <span className="text-sm text-foreground">{guest.guest_name || `Guest #${guest.guest_id}`}</span>
              </div>
              <button
                type="button"
                onClick={() => onUnassign(table.id, guest.id)}
                className="text-xs text-muted-foreground hover:text-red-500 transition-colors"
              >
                Remove
              </button>
            </div>
          ))}
        </div>
      )}

      {isEmpty && (
        <div className="px-5 py-3 text-center text-sm text-muted-foreground">
          No guests assigned
        </div>
      )}

      <div className="border-t px-5 py-3">
        {showAssign ? (
          <form onSubmit={handleAssign} className="space-y-2">
            <select
              value={selectedGuestId}
              onChange={(e) => setSelectedGuestId(e.target.value)}
              className="block w-full rounded-md border border-input bg-background px-3 py-1.5 text-sm text-foreground focus:outline-none focus:ring-2 focus:ring-ring"
            >
              <option value="">Select a guest...</option>
              {guests.map((g) => (
                <option key={g.id} value={g.id}>{g.name}</option>
              ))}
            </select>
            <input
              type="number"
              value={seatNumber}
              onChange={(e) => setSeatNumber(e.target.value)}
              placeholder="Seat number (optional)"
              min={1}
              className="block w-full rounded-md border border-input bg-background px-3 py-1.5 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring"
            />
            <div className="flex items-center gap-2">
              <button
                type="submit"
                className="rounded-md bg-primary px-3 py-1.5 text-xs font-medium text-primary-foreground hover:bg-primary/80 transition-colors"
              >
                Assign
              </button>
              <button
                type="button"
                onClick={() => setShowAssign(false)}
                className="rounded-md border border-input bg-background px-3 py-1.5 text-xs font-medium text-foreground hover:bg-muted transition-colors"
              >
                Cancel
              </button>
            </div>
          </form>
        ) : (
          <button
            type="button"
            onClick={() => setShowAssign(true)}
            disabled={isFull}
            className="w-full rounded-md border border-dashed border-input bg-background px-3 py-2 text-xs text-muted-foreground hover:bg-muted hover:text-foreground transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
          >
            {isFull ? 'Table Full' : `+ Assign Guest (${availableSeats} available)`}
          </button>
        )}
      </div>
    </div>
  );
}
