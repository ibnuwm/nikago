'use client';

import { useState, useEffect } from 'react';
import { useProfile, useUpdateProfile } from '../hooks/use-settings';

export function ProfileForm() {
  const { data: profile, isLoading } = useProfile();
  const updateMutation = useUpdateProfile();

  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [phone, setPhone] = useState('');

  useEffect(() => {
    if (profile?.user) {
      setName(profile.user.name);
      setEmail(profile.user.email);
      setPhone(profile.user.phone ?? '');
    }
  }, [profile]);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    updateMutation.mutate({ name, email, phone: phone || undefined });
  };

  if (isLoading) return <div className="p-4 text-sm text-muted-foreground">Loading...</div>;

  return (
    <form onSubmit={handleSubmit} className="space-y-4">
      <div>
        <label className="block text-sm font-medium mb-1">Name</label>
        <input
          type="text"
          value={name}
          onChange={(e) => setName(e.target.value)}
          className="w-full rounded-md border bg-background px-3 py-2 text-sm"
        />
      </div>
      <div>
        <label className="block text-sm font-medium mb-1">Email</label>
        <input
          type="email"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
          className="w-full rounded-md border bg-background px-3 py-2 text-sm"
        />
      </div>
      <div>
        <label className="block text-sm font-medium mb-1">Phone</label>
        <input
          type="text"
          value={phone}
          onChange={(e) => setPhone(e.target.value)}
          className="w-full rounded-md border bg-background px-3 py-2 text-sm"
        />
      </div>

      {updateMutation.isError && (
        <p className="text-sm text-red-500">{(updateMutation.error as any)?.response?.data?.message ?? 'Update failed.'}</p>
      )}
      {updateMutation.isSuccess && (
        <p className="text-sm text-green-600">Profile updated successfully.</p>
      )}

      <button
        type="submit"
        disabled={updateMutation.isPending}
        className="rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90 disabled:opacity-50"
      >
        {updateMutation.isPending ? 'Saving...' : 'Save Profile'}
      </button>
    </form>
  );
}
