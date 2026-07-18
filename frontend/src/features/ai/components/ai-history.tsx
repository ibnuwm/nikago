'use client';

import { useState } from 'react';
import { useAiHistory } from '@/features/ai/hooks/use-ai';

const FEATURE_LABELS: Record<string, string> = {
  chat: 'Chat',
  story: 'Wedding Story',
  invitation: 'Konten Undangan',
  checklist: 'Checklist',
  budget: 'Anggaran',
  timeline: 'Timeline',
  rundown: 'Rundown',
  caption: 'Caption',
  vendor_recommendation: 'Rekomendasi Vendor',
};

export function AiHistory() {
  const [selectedFeature, setSelectedFeature] = useState('');
  const { data, isLoading } = useAiHistory({ feature: selectedFeature || undefined });

  return (
    <div className="border rounded-lg bg-white shadow-sm">
      <div className="px-4 py-3 border-b bg-gray-50 rounded-t-lg">
        <h3 className="font-semibold text-gray-900">Riwayat AI</h3>
        <p className="text-sm text-gray-500">Riwayat generate konten AI Anda</p>
      </div>

      <div className="p-4">
        <select
          value={selectedFeature}
          onChange={(e) => setSelectedFeature(e.target.value)}
          className="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 mb-4"
        >
          <option value="">Semua Feature</option>
          {Object.entries(FEATURE_LABELS).map(([key, label]) => (
            <option key={key} value={key}>{label}</option>
          ))}
        </select>

        {isLoading ? (
          <div className="text-center py-8 text-gray-500">Memuat...</div>
        ) : !data?.data?.length ? (
          <div className="text-center py-8 text-gray-500">Belum ada riwayat</div>
        ) : (
          <div className="space-y-3 max-h-[500px] overflow-y-auto">
            {data.data.map((item) => (
              <div key={item.id} className="border rounded-lg p-3 hover:bg-gray-50">
                <div className="flex items-center justify-between mb-2">
                  <span className="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-1 rounded">
                    {FEATURE_LABELS[item.feature] || item.feature}
                  </span>
                  <span className="text-xs text-gray-400">
                    {item.created_at ? new Date(item.created_at).toLocaleDateString('id-ID') : ''}
                  </span>
                </div>
                <p className="text-sm text-gray-700 line-clamp-2 mb-1">{item.prompt}</p>
                {item.response && (
                  <p className="text-sm text-gray-500 line-clamp-2">{item.response}</p>
                )}
                <div className="flex items-center gap-3 mt-2 text-xs text-gray-400">
                  <span>Model: {item.model}</span>
                  <span>Tokens: {item.prompt_tokens + item.completion_tokens}</span>
                </div>
              </div>
            ))}
          </div>
        )}
      </div>
    </div>
  );
}
