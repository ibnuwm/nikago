'use client';

import { useEffect } from 'react';
import { useRouter } from 'next/navigation';
import { useCreateInvitation } from '@/features/invitation/hooks/use-invitations';
import { InvitationForm } from '@/features/invitation/components/invitation-form';
import { useAuthStore } from '@/stores/auth-store';
import type { InvitationFormData } from '@/types';

export default function CreateInvitationPage() {
  const token = useAuthStore((s) => s.token);
  const router = useRouter();

  useEffect(() => {
    if (!token) {
      router.push('/login');
    }
  }, [token, router]);

  const createInvitation = useCreateInvitation();

  const handleSubmit = (data: InvitationFormData) => {
    createInvitation.mutate(data, {
      onSuccess: (invitation) => {
        router.push(`/invitations/${invitation.id}`);
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
          <h1 className="text-xl font-bold text-card-foreground">Create Invitation</h1>
        </div>
      </header>

      <main className="mx-auto max-w-2xl px-4 py-8 sm:px-6 lg:px-8">
        <InvitationForm
          onSubmit={handleSubmit}
          isLoading={createInvitation.isPending}
        />
      </main>
    </div>
  );
}
