'use client';

interface NotificationSettingsProps {
  preferences: {
    in_app: boolean;
    email: boolean;
    whatsapp: boolean;
  };
  onToggle: (channel: 'in_app' | 'email' | 'whatsapp') => void;
}

const channelLabels: Record<string, string> = {
  in_app: 'In-App',
  email: 'Email',
  whatsapp: 'WhatsApp',
};

const channelDescriptions: Record<string, string> = {
  in_app: 'Terima notifikasi di dalam aplikasi',
  email: 'Terima notifikasi melalui email',
  whatsapp: 'Terima notifikasi melalui WhatsApp',
};

export function NotificationSettings({ preferences, onToggle }: NotificationSettingsProps) {
  return (
    <div className="space-y-4">
      <h2 className="text-lg font-semibold">Pengaturan Notifikasi</h2>
      <p className="text-sm text-muted-foreground">
        Pilih saluran notifikasi yang ingin kamu aktifkan.
      </p>

      <div className="space-y-3">
        {(Object.keys(preferences) as Array<keyof typeof preferences>).map((channel) => (
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
                checked={preferences[channel]}
                onChange={() => onToggle(channel)}
                className="peer sr-only"
              />
              <div className="h-6 w-11 rounded-full bg-muted after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all after:content-[''] peer-checked:bg-primary peer-checked:after:translate-x-full" />
            </label>
          </div>
        ))}
      </div>
    </div>
  );
}
