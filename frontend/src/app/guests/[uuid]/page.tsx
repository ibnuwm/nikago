'use client';

import { useParams, useRouter } from 'next/navigation';
import Link from 'next/link';
import { Button } from '@/components/ui/button';
import { useGuest, useSendInvitation } from '@/features/guest/hooks/use-guests';
import { useAuthStore } from '@/stores/auth-store';
import { useEffect } from 'react';

export default function GuestDetailPage() {
  const { uuid } = useParams<{ uuid: string }>();
  const token = useAuthStore((s) => s.token);
  const router = useRouter();

  useEffect(() => {
    if (!token) {
      router.push('/login');
    }
  }, [token, router]);

  const { data: guest, isLoading } = useGuest(uuid);
  const sendInvitation = useSendInvitation();

  if (!token) {
    return null;
  }

  if (isLoading) {
    return (
      <div className="min-h-screen bg-background">
        <main className="mx-auto max-w-2xl px-4 py-8">
          <p className="text-center text-sm text-muted-foreground">Loading guest...</p>
        </main>
      </div>
    );
  }

  if (!guest) {
    return (
      <div className="min-h-screen bg-background">
        <main className="mx-auto max-w-2xl px-4 py-8">
          <p className="text-center text-sm text-muted-foreground">Guest not found.</p>
          <div className="mt-4 text-center">
            <Link href="/guests">
              <Button variant="outline">Back to Guests</Button>
            </Link>
          </div>
        </main>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-background">
      <header className="border-b bg-card">
        <div className="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
          <div className="flex items-center gap-4">
            <Link href="/guests">
              <Button variant="outline" size="sm">&larr; Back</Button>
            </Link>
            <h1 className="text-xl font-bold text-card-foreground">{guest.name}</h1>
          </div>
          <div className="flex items-center gap-2">
            <Link href={`/guests/${guest.id}/edit`}>
              <Button variant="outline">Edit</Button>
            </Link>
            {!guest.invitation_sent_at && (
              <Button
                onClick={() => sendInvitation.mutate(guest.id)}
                disabled={sendInvitation.isPending}
              >
                {sendInvitation.isPending ? 'Sending...' : 'Send Invitation'}
              </Button>
            )}
          </div>
        </div>
      </header>

      <main className="mx-auto max-w-2xl px-4 py-8 sm:px-6 lg:px-8">
        <div className="rounded-lg border bg-card p-6">
          <dl className="space-y-4">
            <div className="flex justify-between">
              <dt className="text-sm font-medium text-muted-foreground">Name</dt>
              <dd className="text-sm text-card-foreground">{guest.name}</dd>
            </div>
            <div className="flex justify-between">
              <dt className="text-sm font-medium text-muted-foreground">Phone</dt>
              <dd className="text-sm text-card-foreground">{guest.phone || '-'}</dd>
            </div>
            <div className="flex justify-between">
              <dt className="text-sm font-medium text-muted-foreground">Email</dt>
              <dd className="text-sm text-card-foreground">{guest.email || '-'}</dd>
            </div>
            <div className="flex justify-between">
              <dt className="text-sm font-medium text-muted-foreground">Address</dt>
              <dd className="text-sm text-card-foreground">{guest.address || '-'}</dd>
            </div>
            <div className="flex justify-between">
              <dt className="text-sm font-medium text-muted-foreground">Pax</dt>
              <dd className="text-sm text-card-foreground">{guest.pax}</dd>
            </div>
            <div className="flex justify-between">
              <dt className="text-sm font-medium text-muted-foreground">Status</dt>
              <dd className="text-sm text-card-foreground capitalize">{guest.status}</dd>
            </div>
            <div className="flex justify-between">
              <dt className="text-sm font-medium text-muted-foreground">QR Code</dt>
              <dd className="text-sm font-mono text-card-foreground">{guest.qr_code || '-'}</dd>
            </div>
            <div className="flex justify-between">
              <dt className="text-sm font-medium text-muted-foreground">Invitation Sent</dt>
              <dd className="text-sm text-card-foreground">
                {guest.invitation_sent_at
                  ? new Date(guest.invitation_sent_at).toLocaleDateString()
                  : 'Not sent yet'}
              </dd>
            </div>
            {guest.rsvp && (
              <div className="flex justify-between">
                <dt className="text-sm font-medium text-muted-foreground">RSVP Status</dt>
                <dd className="text-sm text-card-foreground capitalize">
                  {guest.rsvp.status === 'attending' ? 'Attending' : guest.rsvp.status === 'not_attending' ? 'Not Attending' : 'Pending'}
                  {guest.rsvp.total_guests > 0 && ` (${guest.rsvp.total_guests} guests)`}
                </dd>
              </div>
            )}
          </dl>
        </div>
      </main>
    </div>
  );
}
