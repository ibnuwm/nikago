'use client';

import { useState } from 'react';
import { useUpdatePassword } from '../hooks/use-settings';

export function PasswordForm() {
  const updateMutation = useUpdatePassword();
  const [currentPassword, setCurrentPassword] = useState('');
  const [password, setPassword] = useState('');
  const [passwordConfirmation, setPasswordConfirmation] = useState('');

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    updateMutation.mutate(
      { current_password: currentPassword, password, password_confirmation: passwordConfirmation },
      {
        onSuccess: () => {
          setCurrentPassword('');
          setPassword('');
          setPasswordConfirmation('');
        },
      }
    );
  };

  return (
    <form onSubmit={handleSubmit} className="space-y-4">
      <div>
        <label className="block text-sm font-medium mb-1">Current Password</label>
        <input
          type="password"
          value={currentPassword}
          onChange={(e) => setCurrentPassword(e.target.value)}
          className="w-full rounded-md border bg-background px-3 py-2 text-sm"
          required
        />
      </div>
      <div>
        <label className="block text-sm font-medium mb-1">New Password</label>
        <input
          type="password"
          value={password}
          onChange={(e) => setPassword(e.target.value)}
          className="w-full rounded-md border bg-background px-3 py-2 text-sm"
          required
        />
      </div>
      <div>
        <label className="block text-sm font-medium mb-1">Confirm New Password</label>
        <input
          type="password"
          value={passwordConfirmation}
          onChange={(e) => setPasswordConfirmation(e.target.value)}
          className="w-full rounded-md border bg-background px-3 py-2 text-sm"
          required
        />
      </div>

      {updateMutation.isError && (
        <p className="text-sm text-red-500">
          {(updateMutation.error as any)?.response?.data?.errors?.current_password?.[0] ??
           (updateMutation.error as any)?.response?.data?.message ??
           'Password update failed.'}
        </p>
      )}
      {updateMutation.isSuccess && (
        <p className="text-sm text-green-600">Password updated successfully.</p>
      )}

      <button
        type="submit"
        disabled={updateMutation.isPending}
        className="rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90 disabled:opacity-50"
      >
        {updateMutation.isPending ? 'Updating...' : 'Change Password'}
      </button>
    </form>
  );
}
