'use client';

import { useUser } from '@/hooks/use-auth';
import { useAuthStore } from '@/stores/auth-store';
import { useRouter } from 'next/navigation';
import { useEffect } from 'react';
import { Button } from '@/components/ui/button';
import { useLogout } from '@/hooks/use-auth';

export default function DashboardPage() {
  const { data: user, isLoading } = useUser();
  const token = useAuthStore((s) => s.token);
  const router = useRouter();
  const logout = useLogout();

  useEffect(() => {
    if (!token && !isLoading) {
      router.push('/login');
    }
  }, [token, isLoading, router]);

  if (isLoading) {
    return (
      <div className="flex min-h-screen items-center justify-center">
        <p className="text-sm text-gray-500">Loading...</p>
      </div>
    );
  }

  if (!user) {
    return null;
  }

  return (
    <div className="min-h-screen bg-gray-50">
      <header className="border-b bg-white">
        <div className="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
          <h1 className="text-xl font-bold text-gray-900">Nikago</h1>
          <div className="flex items-center gap-4">
            <span className="text-sm text-gray-600">{user.name}</span>
            <Button
              variant="outline"
              size="sm"
              onClick={() => logout.mutate()}
              disabled={logout.isPending}
            >
              {logout.isPending ? 'Signing out...' : 'Sign out'}
            </Button>
          </div>
        </div>
      </header>
      <main className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <h2 className="text-2xl font-bold text-gray-900">Dashboard</h2>
        <p className="mt-2 text-gray-600">
          Welcome back, {user.name}! This is your wedding dashboard.
        </p>
      </main>
    </div>
  );
}
