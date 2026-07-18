'use client';

import { useState, useEffect } from 'react';
import { useNotificationPreferences, useUpdateNotificationPreferences } from '../hooks/use-settings';

const channelLabels: Record<string, string> = {
  in_app: 'In-App',
  email: 'Email',
  whatsapp: 'WhatsApp',
};

const channelDescriptions: Record<string, string> = {
  in_app: 'Receive notifications within the application',
  email: 'Receive notifications via email',
  whatsapp: 'Receive notifications via WhatsApp',
};

export function NotificationPreferencesForm() {
  const { data: preferences, isLoading } = useNotificationPreferences();
  const updateMutation = useUpdateNotificationPreferences();

  const [channels, setChannels] = useState<Record<string, boolean>>({
    in_app: true,
    email: true,
    whatsapp: false,
  });

  useEffect(() => {
    if (preferences) {
      setChannels({
        in_app: preferences.in_app,
        email: preferences.email,
        whatsapp: preferences.whatsapp,
      });
    }
  }, [preferences]);

  const handleToggle = (channel: string) => {
    const updated = { ...channels, [channel]: !channels[channel] };
    setChannels(updated);
    updateMutation.mutate({ [channel]: !channels[channel] });
  };

  if (isLoading) return <div className="p-4 text-sm text-muted-foreground">Loading...</div>;

  return (
    <div className="space-y-4">
      <div className="space-y-3">
        {Object.keys(channels).map((channel) => (
          <div
            key={channel}
            className="flex items-center justify-between rounded-lg border bg-card p-4"
          >
            <div>
              <p className="text-sm font-medium text-card-foreground">
                {channelLabels[channel]}
              </p>
              <p className="text-xs text-muted-foreground">
                {channelDescriptions[channel]}
              </p>
            </div>
            <label className="relative inline-flex cursor-pointer items-center">
              <input
                type="checkbox"
                checked={channels[channel]}
                onChange={() => handleToggle(channel)}
                className="peer sr-only"
              />
              <div className="h-6 w-11 rounded-full bg-muted after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all after:content-[''] peer-checked:bg-primary peer-checked:after:translate-x-full" />
            </label>
          </div>
        ))}
      </div>

      {updateMutation.isError && (
        <p className="text-sm text-red-500">{(updateMutation.error as any)?.response?.data?.message ?? 'Update failed.'}</p>
      )}
      {updateMutation.isSuccess && (
        <p className="text-sm text-green-600">Notification preferences updated.</p>
      )}
    </div>
  );
}
