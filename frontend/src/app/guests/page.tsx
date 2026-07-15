'use client';

import { useState } from 'react';
import Link from 'next/link';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { useGuests, useDeleteGuest, useSendInvitation, useImportGuests, useExportGuests } from '@/features/guest/hooks/use-guests';
import { GuestTable } from '@/features/guest/components/guest-table';
import { useAuthStore } from '@/stores/auth-store';
import { useRouter } from 'next/navigation';
import { useEffect, useRef } from 'react';

export default function GuestsPage() {
  const [search, setSearch] = useState('');
  const [statusFilter, setStatusFilter] = useState('');
  const token = useAuthStore((s) => s.token);
  const router = useRouter();
  const fileInputRef = useRef<HTMLInputElement>(null);

  useEffect(() => {
    if (!token) {
      router.push('/login');
    }
  }, [token, router]);

  const { data: guestsData, isLoading, refetch } = useGuests({
    search,
    status: statusFilter,
  });

  const deleteGuest = useDeleteGuest();
  const sendInvitation = useSendInvitation();
  const importGuests = useImportGuests();
  const exportGuests = useExportGuests();

  const guests = guestsData?.data ?? [];

  const handleImport = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('file', file);

    importGuests.mutate(formData, {
      onSuccess: () => {
        if (fileInputRef.current) {
          fileInputRef.current.value = '';
        }
      },
    });
  };

  const handleExport = () => {
    exportGuests.mutate(undefined, {
      onSuccess: (data) => {
        const csv = data.map((row) => row.join(',')).join('\n');
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'guests.csv';
        a.click();
        URL.revokeObjectURL(url);
      },
    });
  };

  if (!token) {
    return null;
  }

  return (
    <div className="min-h-screen bg-background">
      <header className="border-b bg-card">
        <div className="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
          <h1 className="text-xl font-bold text-card-foreground">Guest List</h1>
          <div className="flex items-center gap-3">
            <input
              ref={fileInputRef}
              type="file"
              accept=".csv"
              onChange={handleImport}
              className="hidden"
            />
            <Button variant="outline" onClick={() => fileInputRef.current?.click()} disabled={importGuests.isPending}>
              {importGuests.isPending ? 'Importing...' : 'Import CSV'}
            </Button>
            <Button variant="outline" onClick={handleExport} disabled={exportGuests.isPending}>
              {exportGuests.isPending ? 'Exporting...' : 'Export CSV'}
            </Button>
            <Link href="/guests/create">
              <Button>Add Guest</Button>
            </Link>
          </div>
        </div>
      </header>

      <main className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        {importGuests.data && (
          <div className="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800 dark:border-green-800 dark:bg-green-950 dark:text-green-300">
            Imported {importGuests.data.imported} guests
            {importGuests.data.failed > 0 && `, ${importGuests.data.failed} failed.`}
          </div>
        )}

        {importGuests.isError && (
          <div className="mb-4 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800 dark:border-red-800 dark:bg-red-950 dark:text-red-300">
            Import failed. Please check your file format.
          </div>
        )}

        <div className="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center">
          <Input
            placeholder="Search guests..."
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
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>
        </div>

        {isLoading ? (
          <div className="py-12 text-center">
            <p className="text-sm text-muted-foreground">Loading guests...</p>
          </div>
        ) : (
          <GuestTable
            guests={guests}
            onDelete={(uuid) => {
              if (confirm('Are you sure you want to delete this guest?')) {
                deleteGuest.mutate(uuid);
              }
            }}
            onSendInvitation={(uuid) => sendInvitation.mutate(uuid)}
            isDeleting={deleteGuest.isPending}
            isSending={sendInvitation.isPending}
          />
        )}
      </main>
    </div>
  );
}
