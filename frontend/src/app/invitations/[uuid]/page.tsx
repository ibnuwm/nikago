'use client';

import { useEffect } from 'react';
import { useRouter, useParams } from 'next/navigation';
import Link from 'next/link';
import { Button } from '@/components/ui/button';
import { useInvitation, useDeleteInvitation, usePublishInvitation, useDraftInvitation } from '@/features/invitation/hooks/use-invitations';
import { useAuthStore } from '@/stores/auth-store';

export default function InvitationDetailPage() {
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
  const deleteInvitation = useDeleteInvitation();
  const publishInvitation = usePublishInvitation();
  const draftInvitation = useDraftInvitation();

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
        <div className="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
          <h1 className="text-xl font-bold text-card-foreground">{invitation.title}</h1>
          <div className="flex gap-2">
            <Link href={`/invitations/${uuid}/edit`}>
              <Button variant="outline">Edit</Button>
            </Link>
            {invitation.status === 'draft' && (
              <Button
                variant="outline"
                onClick={() => publishInvitation.mutate(uuid)}
                disabled={publishInvitation.isPending}
              >
                {publishInvitation.isPending ? 'Publishing...' : 'Publish'}
              </Button>
            )}
            {invitation.status === 'published' && (
              <Button
                variant="outline"
                onClick={() => draftInvitation.mutate(uuid)}
                disabled={draftInvitation.isPending}
              >
                {draftInvitation.isPending ? 'Unpublishing...' : 'Unpublish'}
              </Button>
            )}
            <Button
              variant="destructive"
              onClick={() => {
                if (confirm('Are you sure you want to delete this invitation?')) {
                  deleteInvitation.mutate(uuid, {
                    onSuccess: () => router.push('/invitations'),
                  });
                }
              }}
              disabled={deleteInvitation.isPending}
            >
              {deleteInvitation.isPending ? 'Deleting...' : 'Delete'}
            </Button>
          </div>
        </div>
      </header>

      <main className="mx-auto max-w-2xl px-4 py-8 sm:px-6 lg:px-8">
        <div className="space-y-6">
          <div>
            <h2 className="text-sm font-medium text-muted-foreground">Status</h2>
            <p className="mt-1 text-sm capitalize">{invitation.status}</p>
          </div>
          <div>
            <h2 className="text-sm font-medium text-muted-foreground">Slug</h2>
            <p className="mt-1 text-sm">{invitation.slug}</p>
          </div>
          {invitation.description && (
            <div>
              <h2 className="text-sm font-medium text-muted-foreground">Description</h2>
              <p className="mt-1 text-sm">{invitation.description}</p>
            </div>
          )}
          {invitation.published_at && (
            <div>
              <h2 className="text-sm font-medium text-muted-foreground">Published At</h2>
              <p className="mt-1 text-sm">{new Date(invitation.published_at).toLocaleString()}</p>
            </div>
          )}
          <div>
            <h2 className="text-sm font-medium text-muted-foreground">Created At</h2>
            <p className="mt-1 text-sm">{new Date(invitation.created_at).toLocaleString()}</p>
          </div>
        </div>
      </main>
    </div>
  );
}
