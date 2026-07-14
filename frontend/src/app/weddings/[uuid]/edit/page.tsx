'use client';

import { useRouter, useParams } from 'next/navigation';
import { useEffect } from 'react';
import { useAuthStore } from '@/stores/auth-store';
import { useWedding, useUpdateWedding } from '@/features/wedding/hooks/use-weddings';
import { WeddingForm } from '@/features/wedding/components/wedding-form';
import type { WeddingFormData } from '@/types';

export default function EditWeddingPage() {
  const router = useRouter();
  const params = useParams();
  const uuid = params.uuid as string;
  const token = useAuthStore((s) => s.token);

  const { data: wedding, isLoading } = useWedding(uuid);
  const updateWedding = useUpdateWedding();

  useEffect(() => {
    if (!token) {
      router.push('/login');
    }
  }, [token, router]);

  const handleSubmit = (data: WeddingFormData) => {
    updateWedding.mutate(
      { uuid, data },
      {
        onSuccess: () => {
          router.push(`/weddings/${uuid}`);
        },
      }
    );
  };

  if (!token) {
    return null;
  }

  if (isLoading) {
    return (
      <div className="flex min-h-screen items-center justify-center">
        <p className="text-sm text-muted-foreground">Loading...</p>
      </div>
    );
  }

  if (!wedding) {
    return (
      <div className="flex min-h-screen items-center justify-center">
        <p className="text-sm text-muted-foreground">Wedding not found.</p>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-background">
      <header className="border-b bg-card">
        <div className="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
          <h1 className="text-xl font-bold text-card-foreground">Edit Wedding</h1>
        </div>
      </header>

      <main className="mx-auto max-w-2xl px-4 py-8 sm:px-6 lg:px-8">
        <WeddingForm
          wedding={wedding}
          onSubmit={handleSubmit}
          isLoading={updateWedding.isPending}
        />
      </main>
    </div>
  );
}
