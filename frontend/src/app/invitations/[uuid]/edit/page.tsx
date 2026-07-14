'use client';

import { useEffect } from 'react';
import { useRouter, useParams } from 'next/navigation';
import { useInvitation, useUpdateInvitation } from '@/features/invitation/hooks/use-invitations';
import { InvitationForm } from '@/features/invitation/components/invitation-form';
import { useAuthStore } from '@/stores/auth-store';
import type { InvitationFormData } from '@/types';

export default function EditInvitationPage() {
  const params = useParams();
  const uuid = params.uuid as string;
  const token = useAuthStore((s) => s.token);
  const router = useRouter();

  useEffect(() => {
    if (!token) {
      router.push('/login');
    }
  }, [token, router]);

  const { data: invitation, isLoading } = useInvitation(uuid);
  const updateInvitation = useUpdateInvitation();

  const handleSubmit = (data: InvitationFormData) => {
    updateInvitation.mutate(
      { uuid, data },
      {
        onSuccess: () => {
          router.push(`/invitations/${uuid}`);
        },
      }
    );
  };

  if (!token) {
    return null;
  }

  if (isLoading) {
    return (
      <div className="min-h-screen bg-background flex items-center justify-center">
        <p className="text-sm text-muted-foreground">Loading invitation...</p>
      </div>
    );
  }

  if (!invitation) {
    return (
      <div className="min-h-screen bg-background flex items-center justify-center">
        <p className="text-sm text-muted-foreground">Invitation not found.</p>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-background">
      <header className="border-b bg-card">
        <div className="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
          <h1 className="text-xl font-bold text-card-foreground">Edit Invitation</h1>
        </div>
      </header>

      <main className="mx-auto max-w-2xl px-4 py-8 sm:px-6 lg:px-8">
        <InvitationForm
          invitation={invitation}
          onSubmit={handleSubmit}
          isLoading={updateInvitation.isPending}
        />
      </main>
    </div>
  );
}
