'use client';

import Link from 'next/link';
import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';
import type { Guest } from '@/types';

interface GuestTableProps {
  guests: Guest[];
  onDelete?: (uuid: string) => void;
  onSendInvitation?: (uuid: string) => void;
  isDeleting?: boolean;
  isSending?: boolean;
}

export function GuestTable({ guests, onDelete, onSendInvitation, isDeleting, isSending }: GuestTableProps) {
  if (guests.length === 0) {
    return (
      <div className="py-12 text-center">
        <p className="text-sm text-muted-foreground">No guests found.</p>
      </div>
    );
  }

  return (
    <div className="overflow-x-auto rounded-lg border">
      <table className="min-w-full divide-y">
        <thead className="bg-muted">
          <tr>
            <th className="px-4 py-3 text-left text-xs font-medium uppercase text-muted-foreground">Name</th>
            <th className="px-4 py-3 text-left text-xs font-medium uppercase text-muted-foreground">Phone</th>
            <th className="px-4 py-3 text-left text-xs font-medium uppercase text-muted-foreground">Email</th>
            <th className="px-4 py-3 text-center text-xs font-medium uppercase text-muted-foreground">Pax</th>
            <th className="px-4 py-3 text-center text-xs font-medium uppercase text-muted-foreground">Status</th>
            <th className="px-4 py-3 text-center text-xs font-medium uppercase text-muted-foreground">RSVP</th>
            <th className="px-4 py-3 text-right text-xs font-medium uppercase text-muted-foreground">Actions</th>
          </tr>
        </thead>
        <tbody className="divide-y bg-card">
          {guests.map((guest) => (
            <tr key={guest.id} className="hover:bg-muted/50">
              <td className="whitespace-nowrap px-4 py-3 text-sm font-medium text-card-foreground">
                <Link href={`/guests/${guest.id}`} className="hover:underline">
                  {guest.name}
                </Link>
              </td>
              <td className="whitespace-nowrap px-4 py-3 text-sm text-muted-foreground">
                {guest.phone || '-'}
              </td>
              <td className="whitespace-nowrap px-4 py-3 text-sm text-muted-foreground">
                {guest.email || '-'}
              </td>
              <td className="whitespace-nowrap px-4 py-3 text-center text-sm text-muted-foreground">
                {guest.pax}
              </td>
              <td className="whitespace-nowrap px-4 py-3 text-center">
                <StatusBadge status={guest.status} />
              </td>
              <td className="whitespace-nowrap px-4 py-3 text-center text-sm">
                {guest.rsvp ? (
                  <RsvpBadge status={guest.rsvp.status} />
                ) : (
                  <span className="text-muted-foreground">-</span>
                )}
              </td>
              <td className="whitespace-nowrap px-4 py-3 text-right">
                <div className="flex items-center justify-end gap-2">
                  <Link href={`/guests/${guest.id}/edit`}>
                    <Button variant="outline" size="xs">
                      Edit
                    </Button>
                  </Link>
                  {onSendInvitation && !guest.invitation_sent_at && (
                    <Button
                      variant="outline"
                      size="xs"
                      onClick={() => onSendInvitation(guest.id)}
                      disabled={isSending}
                    >
                      Send Invitation
                    </Button>
                  )}
                  {onDelete && (
                    <Button
                      variant="destructive"
                      size="xs"
                      onClick={() => onDelete(guest.id)}
                      disabled={isDeleting}
                    >
                      Delete
                    </Button>
                  )}
                </div>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}

function StatusBadge({ status }: { status: string }) {
  const variants: Record<string, string> = {
    active: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
    inactive: 'bg-muted text-muted-foreground',
  };

  return (
    <span
      className={cn(
        'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium capitalize',
        variants[status] || variants.inactive
      )}
    >
      {status}
    </span>
  );
}

function RsvpBadge({ status }: { status: string }) {
  const variants: Record<string, string> = {
    attending: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
    not_attending: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
    pending: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
  };

  return (
    <span
      className={cn(
        'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium capitalize',
        variants[status] || variants.pending
      )}
    >
      {status === 'attending' ? 'Yes' : status === 'not_attending' ? 'No' : 'Pending'}
    </span>
  );
}
