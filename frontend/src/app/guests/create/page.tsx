'use client';

import { useRouter } from 'next/navigation';
import { GuestForm } from '@/features/guest/components/guest-form';
import { useCreateGuest, useGuests } from '@/features/guest/hooks/use-guests';
import { useAuthStore } from '@/stores/auth-store';
import { useEffect } from 'react';
import type { GuestFormData } from '@/types';

export default function CreateGuestPage() {
  const token = useAuthStore((s) => s.token);
  const router = useRouter();

  useEffect(() => {
    if (!token) {
      router.push('/login');
    }
  }, [token, router]);

  const createGuest = useCreateGuest();

  const { data: guestsData } = useGuests({ per_page: 1 });
  const firstWeddingId = guestsData?.data?.[0]?.wedding_id ?? 0;

  const handleSubmit = (data: GuestFormData) => {
    createGuest.mutate(data, {
      onSuccess: () => {
        router.push('/guests');
      },
    });
  };

  if (!token) {
    return null;
  }

  return (
    <div className="min-h-screen bg-background">
      <header className="border-b bg-card">
        <div className="mx-auto flex max-w-7xl items-center px-4 py-4 sm:px-6 lg:px-8">
          <h1 className="text-xl font-bold text-card-foreground">Add Guest</h1>
        </div>
      </header>

      <main className="mx-auto max-w-2xl px-4 py-8 sm:px-6 lg:px-8">
        <div className="rounded-lg border bg-card p-6">
          <GuestForm
            weddingId={firstWeddingId}
            onSubmit={handleSubmit}
            isLoading={createGuest.isPending}
          />
        </div>
      </main>
    </div>
  );
}
