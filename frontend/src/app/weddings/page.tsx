'use client';

import { useState } from 'react';
import Link from 'next/link';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { useWeddings, useDeleteWedding, usePublishWedding, useArchiveWedding } from '@/features/wedding/hooks/use-weddings';
import { WeddingCard } from '@/features/wedding/components/wedding-card';
import { useAuthStore } from '@/stores/auth-store';
import { useRouter } from 'next/navigation';
import { useEffect } from 'react';

export default function WeddingsPage() {
  const [search, setSearch] = useState('');
  const [statusFilter, setStatusFilter] = useState('');
  const token = useAuthStore((s) => s.token);
  const router = useRouter();

  useEffect(() => {
    if (!token) {
      router.push('/login');
    }
  }, [token, router]);

  const { data: weddingsData, isLoading } = useWeddings({
    search,
    status: statusFilter,
  });

  const deleteWedding = useDeleteWedding();
  const publishWedding = usePublishWedding();
  const archiveWedding = useArchiveWedding();

  const weddings = weddingsData?.data ?? [];

  if (!token) {
    return null;
  }

  return (
    <div className="min-h-screen bg-background">
      <header className="border-b bg-card">
        <div className="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
          <h1 className="text-xl font-bold text-card-foreground">My Weddings</h1>
          <Link href="/weddings/create">
            <Button>Create Wedding</Button>
          </Link>
        </div>
      </header>

      <main className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div className="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center">
          <Input
            placeholder="Search weddings..."
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
            <option value="archived">Archived</option>
          </select>
        </div>

        {isLoading ? (
          <div className="py-12 text-center">
            <p className="text-sm text-muted-foreground">Loading weddings...</p>
          </div>
        ) : weddings.length === 0 ? (
          <div className="py-12 text-center">
            <p className="text-sm text-muted-foreground">No weddings found.</p>
            <Link href="/weddings/create" className="mt-4 inline-block">
              <Button>Create your first wedding</Button>
            </Link>
          </div>
        ) : (
          <div className="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            {weddings.map((wedding) => (
              <WeddingCard
                key={wedding.id}
                wedding={wedding}
                onPublish={(uuid) => publishWedding.mutate(uuid)}
                onArchive={(uuid) => archiveWedding.mutate(uuid)}
                onDelete={(uuid) => {
                  if (confirm('Are you sure you want to delete this wedding?')) {
                    deleteWedding.mutate(uuid);
                  }
                }}
                isPublishing={publishWedding.isPending}
                isArchiving={archiveWedding.isPending}
                isDeleting={deleteWedding.isPending}
              />
            ))}
          </div>
        )}
      </main>
    </div>
  );
}
