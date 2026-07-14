'use client';

import { useState, useEffect } from 'react';
import Link from 'next/link';
import { useRouter } from 'next/navigation';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { useInvitations, useDeleteInvitation, usePublishInvitation, useDraftInvitation, useDuplicateInvitation } from '@/features/invitation/hooks/use-invitations';
import { InvitationCard } from '@/features/invitation/components/invitation-card';
import { useAuthStore } from '@/stores/auth-store';

export default function InvitationsPage() {
  const [search, setSearch] = useState('');
  const [statusFilter, setStatusFilter] = useState('');
  const token = useAuthStore((s) => s.token);
  const router = useRouter();

  useEffect(() => {
    if (!token) {
      router.push('/login');
    }
  }, [token, router]);

  const { data: invitationsData, isLoading } = useInvitations({
    search,
    status: statusFilter,
  });

  const deleteInvitation = useDeleteInvitation();
  const publishInvitation = usePublishInvitation();
  const draftInvitation = useDraftInvitation();
  const duplicateInvitation = useDuplicateInvitation();

  const invitations = invitationsData?.data ?? [];

  if (!token) {
    return null;
  }

  return (
    <div className="min-h-screen bg-background">
      <header className="border-b bg-card">
        <div className="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
          <h1 className="text-xl font-bold text-card-foreground">My Invitations</h1>
          <Link href="/invitations/create">
            <Button>Create Invitation</Button>
          </Link>
        </div>
      </header>

      <main className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div className="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center">
          <Input
            placeholder="Search invitations..."
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            className="max-w-sm"
          />
          <select
            value={statusFilter}
            onChange={(e) => setStatusFilter(e.target.value)}
            className="rounded-md border bg-background px-3 py-2 text-sm"
          >
            <option value="">All Status</option>
            <option value="draft">Draft</option>
            <option value="published">Published</option>
          </select>
        </div>

        {isLoading ? (
          <div className="py-12 text-center">
            <p className="text-sm text-muted-foreground">Loading invitations...</p>
          </div>
        ) : invitations.length === 0 ? (
          <div className="py-12 text-center">
            <p className="text-sm text-muted-foreground">No invitations found.</p>
            <Link href="/invitations/create" className="mt-4 inline-block">
              <Button>Create your first invitation</Button>
            </Link>
          </div>
        ) : (
          <div className="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            {invitations.map((invitation) => (
              <InvitationCard
                key={invitation.id}
                invitation={invitation}
                onPublish={(uuid) => publishInvitation.mutate(uuid)}
                onDraft={(uuid) => draftInvitation.mutate(uuid)}
                onDuplicate={(uuid) => duplicateInvitation.mutate(uuid)}
                onDelete={(uuid) => {
                  if (confirm('Are you sure you want to delete this invitation?')) {
                    deleteInvitation.mutate(uuid);
                  }
                }}
                isPublishing={publishInvitation.isPending}
                isDrafting={draftInvitation.isPending}
                isDuplicating={duplicateInvitation.isPending}
                isDeleting={deleteInvitation.isPending}
              />
            ))}
          </div>
        )}
      </main>
    </div>
  );
}
