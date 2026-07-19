'use client';

import { useEffect, useState } from 'react';
import { useRouter } from 'next/navigation';
import { useAuthStore } from '@/stores/auth-store';
import { useUser } from '@/hooks/use-auth';
import { useIntegrations, useDisconnectGoogle, useDisconnectCalendar, useDisconnectWhatsapp, useTestIntegration } from '@/features/integration/hooks/use-integrations';
import type { UserIntegration } from '@/types';
import {
  Globe,
  Calendar,
  MapPin,
  MessageCircle,
  CreditCard,
  Cloud,
  Video,
  Link2,
  Link2Off,
  CheckCircle2,
  XCircle,
  Loader2,
} from 'lucide-react';

const categoryIcons: Record<string, React.ReactNode> = {
  Authentication: <Globe className="w-5 h-5" />,
  Calendar: <Calendar className="w-5 h-5" />,
  Maps: <MapPin className="w-5 h-5" />,
  Communication: <MessageCircle className="w-5 h-5" />,
  Payment: <CreditCard className="w-5 h-5" />,
  Storage: <Cloud className="w-5 h-5" />,
  Meeting: <Video className="w-5 h-5" />,
  Streaming: <Video className="w-5 h-5" />,
};

export default function IntegrationsPage() {
  const token = useAuthStore((s) => s.token);
  const router = useRouter();
  const { data: user, isLoading: isUserLoading } = useUser();
  const { data: integrations, isLoading } = useIntegrations();
  const testMutation = useTestIntegration();
  const disconnectGoogle = useDisconnectGoogle();
  const disconnectCalendar = useDisconnectCalendar();
  const disconnectWhatsapp = useDisconnectWhatsapp();

  const [testResults, setTestResults] = useState<Record<string, string>>({});

  useEffect(() => {
    if (!token && !isUserLoading) {
      router.push('/login');
    }
  }, [token, isUserLoading, router]);

  if (!token || !user) return null;

  const handleTest = async (code: string) => {
    setTestResults((prev) => ({ ...prev, [code]: 'testing' }));
    try {
      const result = await testMutation.mutateAsync(code);
      setTestResults((prev) => ({ ...prev, [code]: result.status }));
    } catch {
      setTestResults((prev) => ({ ...prev, [code]: 'error' }));
    }
  };

  const handleDisconnect = (code: string) => {
    switch (code) {
      case 'GOOGLE_OAUTH': disconnectGoogle.mutate(); break;
      case 'GOOGLE_CALENDAR': disconnectCalendar.mutate(); break;
      case 'WHATSAPP': disconnectWhatsapp.mutate(); break;
    }
  };

  const grouped = integrations?.reduce<Record<string, UserIntegration[]>>((acc, item) => {
    if (!acc[item.category]) acc[item.category] = [];
    acc[item.category].push(item);
    return acc;
  }, {});

  return (
    <div className="min-h-screen bg-gray-50">
      <div className="max-w-4xl mx-auto px-4 py-6">
        <div className="mb-6">
          <h1 className="text-2xl font-bold text-gray-900">Integrations</h1>
          <p className="text-sm text-gray-500">Connect Nikago with third-party services</p>
        </div>

        {isLoading ? (
          <div className="flex items-center justify-center py-12">
            <Loader2 className="w-6 h-6 animate-spin text-gray-400" />
          </div>
        ) : (
          <div className="space-y-8">
            {grouped && Object.entries(grouped).map(([category, items]) => (
              <div key={category}>
                <div className="flex items-center gap-2 mb-3">
                  {categoryIcons[category]}
                  <h2 className="text-lg font-semibold text-gray-800">{category}</h2>
                </div>
                <div className="grid gap-3">
                  {items.map((integration) => (
                    <div
                      key={integration.code}
                      className="flex items-center justify-between rounded-lg border bg-white p-4"
                    >
                      <div className="flex items-center gap-3">
                        <div className={`p-2 rounded-lg ${integration.is_connected ? 'bg-green-50' : 'bg-gray-50'}`}>
                          {categoryIcons[integration.category] || <Link2 className="w-5 h-5" />}
                        </div>
                        <div>
                          <p className="text-sm font-medium text-gray-900">{integration.name}</p>
                          <p className="text-xs text-gray-500">{integration.description}</p>
                        </div>
                      </div>

                      <div className="flex items-center gap-2">
                        {testResults[integration.code] === 'testing' && (
                          <Loader2 className="w-4 h-4 animate-spin text-gray-400" />
                        )}
                        {testResults[integration.code] === 'connected' && (
                          <CheckCircle2 className="w-4 h-4 text-green-500" />
                        )}
                        {testResults[integration.code] === 'disconnected' && (
                          <XCircle className="w-4 h-4 text-red-400" />
                        )}

                        <button
                          onClick={() => handleTest(integration.code)}
                          className="rounded-md border px-2.5 py-1 text-xs font-medium hover:bg-gray-50"
                        >
                          Test
                        </button>

                        {integration.is_connected ? (
                          <button
                            onClick={() => handleDisconnect(integration.code)}
                            className="inline-flex items-center gap-1 rounded-md border border-red-200 px-2.5 py-1 text-xs font-medium text-red-600 hover:bg-red-50"
                          >
                            <Link2Off className="w-3 h-3" />
                            Disconnect
                          </button>
                        ) : (
                          <span className="inline-flex items-center gap-1 rounded-md border border-gray-200 px-2.5 py-1 text-xs font-medium text-gray-400">
                            <Link2 className="w-3 h-3" />
                            Not Connected
                          </span>
                        )}
                      </div>
                    </div>
                  ))}
                </div>
              </div>
            ))}
          </div>
        )}
      </div>
    </div>
  );
}
