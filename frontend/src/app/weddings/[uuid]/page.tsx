'use client';

import { useRouter, useParams } from 'next/navigation';
import { useEffect } from 'react';
import Link from 'next/link';
import { useAuthStore } from '@/stores/auth-store';
import { useWedding, useDeleteWedding, usePublishWedding, useArchiveWedding } from '@/features/wedding/hooks/use-weddings';
import { Button } from '@/components/ui/button';

export default function WeddingDetailPage() {
  const router = useRouter();
  const params = useParams();
  const uuid = params.uuid as string;
  const token = useAuthStore((s) => s.token);

  const { data: wedding, isLoading } = useWedding(uuid);
  const deleteWedding = useDeleteWedding();
  const publishWedding = usePublishWedding();
  const archiveWedding = useArchiveWedding();

  useEffect(() => {
    if (!token) {
      router.push('/login');
    }
  }, [token, router]);

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
        <div className="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
          <h1 className="text-xl font-bold text-card-foreground">{wedding.title}</h1>
          <Link href="/weddings">
            <Button variant="outline">Back to List</Button>
          </Link>
        </div>
      </header>

      <main className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div className="grid grid-cols-1 gap-6 lg:grid-cols-3">
          <div className="lg:col-span-2">
            <div className="rounded-lg border bg-card p-6 shadow-sm">
              <h2 className="text-lg font-semibold text-card-foreground">Wedding Details</h2>
              <dl className="mt-4 space-y-4">
                <div>
                  <dt className="text-sm font-medium text-muted-foreground">Title</dt>
                  <dd className="mt-1 text-sm text-card-foreground">{wedding.title}</dd>
                </div>
                <div>
                  <dt className="text-sm font-medium text-muted-foreground">Slug</dt>
                  <dd className="mt-1 text-sm text-card-foreground">{wedding.slug}</dd>
                </div>
                <div>
                  <dt className="text-sm font-medium text-muted-foreground">Status</dt>
                  <dd className="mt-1 text-sm capitalize text-card-foreground">{wedding.status}</dd>
                </div>
                {wedding.theme && (
                  <div>
                    <dt className="text-sm font-medium text-muted-foreground">Theme</dt>
                    <dd className="mt-1 text-sm text-card-foreground">{wedding.theme}</dd>
                  </div>
                )}
                <div>
                  <dt className="text-sm font-medium text-muted-foreground">Created</dt>
                  <dd className="mt-1 text-sm text-card-foreground">
                    {new Date(wedding.created_at).toLocaleDateString()}
                  </dd>
                </div>
                {wedding.published_at && (
                  <div>
                    <dt className="text-sm font-medium text-muted-foreground">Published</dt>
                    <dd className="mt-1 text-sm text-card-foreground">
                      {new Date(wedding.published_at).toLocaleDateString()}
                    </dd>
                  </div>
                )}
              </dl>
            </div>
          </div>

          <div>
            <div className="rounded-lg border bg-card p-6 shadow-sm">
              <h2 className="text-lg font-semibold text-card-foreground">Actions</h2>
              <div className="mt-4 space-y-2">
                <Link href={`/weddings/${wedding.id}/edit`} className="block">
                  <Button variant="outline" className="w-full">
                    Edit Wedding
                  </Button>
                </Link>
                {wedding.status === 'draft' && (
                  <Button
                    variant="outline"
                    className="w-full"
                    onClick={() => publishWedding.mutate(wedding.id)}
                    disabled={publishWedding.isPending}
                  >
                    {publishWedding.isPending ? 'Publishing...' : 'Publish'}
                  </Button>
                )}
                {wedding.status === 'published' && (
                  <Button
                    variant="outline"
                    className="w-full"
                    onClick={() => archiveWedding.mutate(wedding.id)}
                    disabled={archiveWedding.isPending}
                  >
                    {archiveWedding.isPending ? 'Archiving...' : 'Archive'}
                  </Button>
                )}
                <Button
                  variant="destructive"
                  className="w-full"
                  onClick={() => {
                    if (confirm('Are you sure you want to delete this wedding?')) {
                      deleteWedding.mutate(wedding.id, {
                        onSuccess: () => {
                          router.push('/weddings');
                        },
                      });
                    }
                  }}
                  disabled={deleteWedding.isPending}
                >
                  {deleteWedding.isPending ? 'Deleting...' : 'Delete'}
                </Button>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  );
}
