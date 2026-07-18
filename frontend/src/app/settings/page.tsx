'use client';

import { useEffect, useState } from 'react';
import { useRouter } from 'next/navigation';
import { useAuthStore } from '@/stores/auth-store';
import { useUser } from '@/hooks/use-auth';
import { ProfileForm } from '@/features/settings/components/profile-form';
import { PasswordForm } from '@/features/settings/components/password-form';
import { PreferencesForm } from '@/features/settings/components/preferences-form';
import { NotificationPreferencesForm } from '@/features/settings/components/notification-preferences-form';
import {
  User,
  Lock,
  Palette,
  Bell,
} from 'lucide-react';

type Tab = 'profile' | 'password' | 'preferences' | 'notifications';

const TABS: { key: Tab; label: string; icon: React.ReactNode }[] = [
  { key: 'profile', label: 'Profile', icon: <User className="w-4 h-4" /> },
  { key: 'password', label: 'Password', icon: <Lock className="w-4 h-4" /> },
  { key: 'preferences', label: 'Preferences', icon: <Palette className="w-4 h-4" /> },
  { key: 'notifications', label: 'Notifications', icon: <Bell className="w-4 h-4" /> },
];

export default function SettingsPage() {
  const [tab, setTab] = useState<Tab>('profile');
  const token = useAuthStore((s) => s.token);
  const router = useRouter();
  const { data: user, isLoading: isUserLoading } = useUser();

  useEffect(() => {
    if (!token && !isUserLoading) {
      router.push('/login');
    }
  }, [token, isUserLoading, router]);

  if (!token || !user) return null;

  return (
    <div className="min-h-screen bg-gray-50">
      <div className="max-w-4xl mx-auto px-4 py-6">
        <div className="mb-6">
          <h1 className="text-2xl font-bold text-gray-900">Settings</h1>
          <p className="text-sm text-gray-500">Manage your account settings and preferences</p>
        </div>

        <div className="flex space-x-1 border-b mb-6 overflow-x-auto">
          {TABS.map((t) => (
            <button
              key={t.key}
              onClick={() => setTab(t.key)}
              className={`flex items-center gap-1.5 px-3 py-2 text-sm font-medium border-b-2 transition-colors whitespace-nowrap ${
                tab === t.key
                  ? 'border-blue-600 text-blue-600'
                  : 'border-transparent text-gray-500 hover:text-gray-700'
              }`}
            >
              {t.icon}
              {t.label}
            </button>
          ))}
        </div>

        <div className="bg-white rounded-lg border p-6">
          {tab === 'profile' && <ProfileForm />}
          {tab === 'password' && <PasswordForm />}
          {tab === 'preferences' && <PreferencesForm />}
          {tab === 'notifications' && <NotificationPreferencesForm />}
        </div>
      </div>
    </div>
  );
}
