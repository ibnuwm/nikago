'use client';

import { useParams, useRouter } from 'next/navigation';
import Link from 'next/link';
import { Button } from '@/components/ui/button';
import { GuestForm } from '@/features/guest/components/guest-form';
import { useGuest, useUpdateGuest } from '@/features/guest/hooks/use-guests';
import { useAuthStore } from '@/stores/auth-store';
import { useEffect } from 'react';
import type { GuestFormData } from '@/types';

export default function EditGuestPage() {
  const { uuid } = useParams<{ uuid: string }>();
  const token = useAuthStore((s) => s.token);
  const router = useRouter();

  useEffect(() => {
    if (!token) {
      router.push('/login');
    }
  }, [token, router]);

  const { data: guest, isLoading } = useGuest(uuid);
  const updateGuest = useUpdateGuest();

  const handleSubmit = (data: GuestFormData) => {
    updateGuest.mutate(
      { uuid, data },
      {
        onSuccess: () => {
          router.push(`/guests/${uuid}`);
        },
      }
    );
  };

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
        <div className="mx-auto flex max-w-7xl items-center gap-4 px-4 py-4 sm:px-6 lg:px-8">
          <Link href={`/guests/${uuid}`}>
            <Button variant="outline" size="sm">&larr; Back</Button>
          </Link>
          <h1 className="text-xl font-bold text-card-foreground">Edit Guest</h1>
        </div>
      </header>

      <main className="mx-auto max-w-2xl px-4 py-8 sm:px-6 lg:px-8">
        <div className="rounded-lg border bg-card p-6">
          <GuestForm
            guest={guest}
            weddingId={guest.wedding_id}
            onSubmit={handleSubmit}
            isLoading={updateGuest.isPending}
          />
        </div>
      </main>
    </div>
  );
}
