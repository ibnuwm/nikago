'use client';

import { useState, useEffect } from 'react';
import { usePreferences, useUpdatePreferences } from '../hooks/use-settings';

export function PreferencesForm() {
  const { data: preferences, isLoading } = usePreferences();
  const updateMutation = useUpdatePreferences();

  const [theme, setTheme] = useState('light');
  const [language, setLanguage] = useState('id');
  const [timezone, setTimezone] = useState('UTC');

  useEffect(() => {
    if (preferences) {
      setTheme(preferences.theme);
      setLanguage(preferences.language);
      setTimezone(preferences.timezone);
    }
  }, [preferences]);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    updateMutation.mutate({ theme, language, timezone });
  };

  if (isLoading) return <div className="p-4 text-sm text-muted-foreground">Loading...</div>;

  return (
    <form onSubmit={handleSubmit} className="space-y-4">
      <div>
        <label className="block text-sm font-medium mb-1">Theme</label>
        <select
          value={theme}
          onChange={(e) => setTheme(e.target.value)}
          className="w-full rounded-md border bg-background px-3 py-2 text-sm"
        >
          <option value="light">Light</option>
          <option value="dark">Dark</option>
          <option value="system">System</option>
        </select>
      </div>
      <div>
        <label className="block text-sm font-medium mb-1">Language</label>
        <select
          value={language}
          onChange={(e) => setLanguage(e.target.value)}
          className="w-full rounded-md border bg-background px-3 py-2 text-sm"
        >
          <option value="id">Indonesia</option>
          <option value="en">English</option>
        </select>
      </div>
      <div>
        <label className="block text-sm font-medium mb-1">Timezone</label>
        <select
          value={timezone}
          onChange={(e) => setTimezone(e.target.value)}
          className="w-full rounded-md border bg-background px-3 py-2 text-sm"
        >
          <option value="UTC">UTC</option>
          <option value="Asia/Jakarta">Asia/Jakarta (WIB)</option>
          <option value="Asia/Makassar">Asia/Makassar (WITA)</option>
          <option value="Asia/Jayapura">Asia/Jayapura (WIT)</option>
        </select>
      </div>

      {updateMutation.isError && (
        <p className="text-sm text-red-500">{(updateMutation.error as any)?.response?.data?.message ?? 'Update failed.'}</p>
      )}
      {updateMutation.isSuccess && (
        <p className="text-sm text-green-600">Preferences saved successfully.</p>
      )}

      <button
        type="submit"
        disabled={updateMutation.isPending}
        className="rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90 disabled:opacity-50"
      >
        {updateMutation.isPending ? 'Saving...' : 'Save Preferences'}
      </button>
    </form>
  );
}
