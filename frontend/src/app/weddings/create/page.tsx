'use client';

import { useRouter } from 'next/navigation';
import { useEffect } from 'react';
import { useAuthStore } from '@/stores/auth-store';
import { useCreateWedding } from '@/features/wedding/hooks/use-weddings';
import { WeddingForm } from '@/features/wedding/components/wedding-form';
import type { WeddingFormData } from '@/types';

export default function CreateWeddingPage() {
  const router = useRouter();
  const token = useAuthStore((s) => s.token);
  const createWedding = useCreateWedding();

  useEffect(() => {
    if (!token) {
      router.push('/login');
    }
  }, [token, router]);

  const handleSubmit = (data: WeddingFormData) => {
    createWedding.mutate(data, {
      onSuccess: (wedding) => {
        router.push(`/weddings/${wedding.id}`);
      },
    });
  };

  if (!token) {
    return null;
  }

  return (
    <div className="min-h-screen bg-background">
      <header className="border-b bg-card">
        <div className="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
          <h1 className="text-xl font-bold text-card-foreground">Create Wedding</h1>
        </div>
      </header>

      <main className="mx-auto max-w-2xl px-4 py-8 sm:px-6 lg:px-8">
        <WeddingForm
          onSubmit={handleSubmit}
          isLoading={createWedding.isPending}
        />
      </main>
    </div>
  );
}
