'use client';

import { useState } from 'react';
import { useAiGenerate } from '@/features/ai/hooks/use-ai';

interface AiGeneratorProps {
  feature: string;
  title: string;
  description: string;
  placeholder: string;
  inputLabel: string;
  contextFields?: Array<{ key: string; label: string; type: string; placeholder?: string }>;
}

const FEATURE_LABELS: Record<string, string> = {
  story: 'Wedding Story',
  invitation: 'Konten Undangan',
  checklist: 'Checklist',
  budget: 'Anggaran',
  timeline: 'Timeline',
  rundown: 'Rundown',
  caption: 'Caption',
  vendor_recommendation: 'Rekomendasi Vendor',
};

export function AiGenerator({ feature, title, description, placeholder, inputLabel, contextFields }: AiGeneratorProps) {
  const [prompt, setPrompt] = useState('');
  const [context, setContext] = useState<Record<string, string>>({});
  const generateMutation = useAiGenerate();

  const handleGenerate = async () => {
    const contextText = contextFields
      ?.filter((f) => context[f.key]?.trim())
      .map((f) => `${f.label}: ${context[f.key]}`)
      .join('\n');

    const fullPrompt = contextText ? `${contextText}\n\n${prompt}` : prompt;

    await generateMutation.mutateAsync({ feature, prompt: fullPrompt });
  };

  return (
    <div className="border rounded-lg bg-white shadow-sm">
      <div className="px-4 py-3 border-b bg-gray-50 rounded-t-lg">
        <h3 className="font-semibold text-gray-900">{title}</h3>
        <p className="text-sm text-gray-500">{description}</p>
      </div>

      <div className="p-4 space-y-4">
        {contextFields && contextFields.length > 0 && (
          <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
            {contextFields.map((field) => (
              <div key={field.key}>
                <label className="block text-sm font-medium text-gray-700 mb-1">{field.label}</label>
                <input
                  type={field.type}
                  value={context[field.key] || ''}
                  onChange={(e) => setContext((prev) => ({ ...prev, [field.key]: e.target.value }))}
                  placeholder={field.placeholder}
                  className="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
              </div>
            ))}
          </div>
        )}

        <div>
          <label className="block text-sm font-medium text-gray-700 mb-1">{inputLabel}</label>
          <textarea
            value={prompt}
            onChange={(e) => setPrompt(e.target.value)}
            placeholder={placeholder}
            rows={4}
            className="w-full border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
          />
        </div>

        <button
          onClick={handleGenerate}
          disabled={!prompt.trim() || generateMutation.isPending}
          className="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          {generateMutation.isPending ? 'Memproses...' : `Generate ${title}`}
        </button>

        {generateMutation.isSuccess && generateMutation.data && (
          <div className="mt-4">
            <label className="block text-sm font-medium text-gray-700 mb-1">Hasil</label>
            <div className="bg-gray-50 border rounded-lg p-4 whitespace-pre-wrap text-sm text-gray-900">
              {generateMutation.data.content}
            </div>
          </div>
        )}

        {generateMutation.isError && (
          <div className="text-red-600 text-sm p-3 bg-red-50 border border-red-200 rounded-lg">
            Gagal menghasilkan konten. Silakan coba lagi.
          </div>
        )}
      </div>
    </div>
  );
}
