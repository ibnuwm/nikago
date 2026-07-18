'use client';

import { useEffect, useState } from 'react';
import { useRouter } from 'next/navigation';
import { useAuthStore } from '@/stores/auth-store';
import { useUser } from '@/hooks/use-auth';
import { AiChat } from '@/features/ai/components/ai-chat';
import { AiGenerator } from '@/features/ai/components/ai-generator';
import { AiHistory } from '@/features/ai/components/ai-history';
import { useAiUsage, useAiModels } from '@/features/ai/hooks/use-ai';

type Tab = 'chat' | 'content' | 'history';

const GENERATORS = [
  {
    feature: 'story',
    title: 'Wedding Story',
    description: 'Buat cerita pernikahan yang romantis',
    placeholder: 'Ceritakan tentang momen spesial Anda berdua...',
    inputLabel: 'Cerita Anda',
    contextFields: [
      { key: 'partner1', label: 'Nama Pasangan 1', type: 'text', placeholder: 'Nama calon pengantin 1' },
      { key: 'partner2', label: 'Nama Pasangan 2', type: 'text', placeholder: 'Nama calon pengantin 2' },
      { key: 'meeting', label: 'Cerita Pertemuan', type: 'text', placeholder: 'Bagaimana kalian bertemu?' },
    ],
  },
  {
    feature: 'invitation',
    title: 'Konten Undangan',
    description: 'Buat teks undangan pernikahan',
    placeholder: 'Masukkan detail undangan...',
    inputLabel: 'Detail Undangan',
    contextFields: [
      { key: 'partner1', label: 'Nama Mempelai 1', type: 'text' },
      { key: 'partner2', label: 'Nama Mempelai 2', type: 'text' },
      { key: 'date', label: 'Tanggal Pernikahan', type: 'date' },
      { key: 'location', label: 'Lokasi', type: 'text', placeholder: 'Nama gedung/tempat' },
    ],
  },
  {
    feature: 'checklist',
    title: 'Checklist Pernikahan',
    description: 'Buat checklist persiapan pernikahan',
    placeholder: 'Sebutkan kebutuhan spesifik...',
    inputLabel: 'Kebutuhan',
    contextFields: [
      { key: 'months', label: 'Waktu Persiapan (bulan)', type: 'number', placeholder: '6' },
      { key: 'guestCount', label: 'Jumlah Tamu', type: 'number', placeholder: '200' },
    ],
  },
  {
    feature: 'budget',
    title: 'Anggaran Pernikahan',
    description: 'Buat rencana anggaran pernikahan',
    placeholder: 'Masukkan total anggaran dan prioritas...',
    inputLabel: 'Detail Anggaran',
    contextFields: [
      { key: 'totalBudget', label: 'Total Budget', type: 'text', placeholder: 'Rp 100.000.000' },
      { key: 'guestCount', label: 'Jumlah Tamu', type: 'number', placeholder: '200' },
    ],
  },
  {
    feature: 'timeline',
    title: 'Timeline Pernikahan',
    description: 'Buat timeline persiapan pernikahan',
    placeholder: 'Masukkan tanggal pernikahan...',
    inputLabel: 'Detail Timeline',
    contextFields: [
      { key: 'weddingDate', label: 'Tanggal Pernikahan', type: 'date' },
      { key: 'months', label: 'Sisa Waktu (bulan)', type: 'number', placeholder: '6' },
    ],
  },
  {
    feature: 'rundown',
    title: 'Rundown Acara',
    description: 'Buat rundown acara pernikahan',
    placeholder: 'Jelaskan konsep acara...',
    inputLabel: 'Detail Acara',
    contextFields: [
      { key: 'startTime', label: 'Jam Mulai', type: 'time' },
      { key: 'concept', label: 'Konsep Acara', type: 'text', placeholder: 'Contoh: adat, modern, outdoor' },
    ],
  },
  {
    feature: 'caption',
    title: 'Caption Media Sosial',
    description: 'Buat caption untuk postingan pernikahan',
    placeholder: 'Jelaskan foto/momen yang ingin di-caption...',
    inputLabel: 'Deskripsi Momen',
    contextFields: [
      { key: 'platform', label: 'Platform', type: 'text', placeholder: 'Instagram, Facebook, dll' },
      { key: 'tone', label: 'Nada', type: 'text', placeholder: 'Romantis, lucu, formal' },
    ],
  },
  {
    feature: 'vendor_recommendation',
    title: 'Rekomendasi Vendor',
    description: 'Dapatkan rekomendasi vendor pernikahan',
    placeholder: 'Sebutkan jenis vendor yang dibutuhkan...',
    inputLabel: 'Kebutuhan Vendor',
    contextFields: [
      { key: 'vendorType', label: 'Jenis Vendor', type: 'text', placeholder: 'Catering, fotografer, dll' },
      { key: 'budget', label: 'Budget', type: 'text', placeholder: 'Rp 50.000.000' },
      { key: 'location', label: 'Lokasi', type: 'text', placeholder: 'Kota' },
    ],
  },
];

export default function AiPage() {
  const [tab, setTab] = useState<Tab>('chat');
  const [generatorIndex, setGeneratorIndex] = useState(0);
  const token = useAuthStore((s) => s.token);
  const router = useRouter();
  const { data: user, isLoading: isUserLoading } = useUser();
  const { data: usage } = useAiUsage();

  useEffect(() => {
    if (!token && !isUserLoading) {
      router.push('/login');
    }
  }, [token, isUserLoading, router]);

  if (!token || !user) return null;

  return (
    <div className="min-h-screen bg-gray-50">
      <div className="max-w-7xl mx-auto px-4 py-6">
        <div className="flex items-center justify-between mb-6">
          <div>
            <h1 className="text-2xl font-bold text-gray-900">AI Wedding Assistant</h1>
            <p className="text-sm text-gray-500">Asisten AI untuk persiapan pernikahan Anda</p>
          </div>
          {usage && (
            <div className="text-right text-sm text-gray-500">
              <p>Tokens: {usage.total_tokens.toLocaleString()}</p>
              <p>Requests: {usage.total_requests}</p>
            </div>
          )}
        </div>

        <div className="flex space-x-1 border-b mb-6">
          {(['chat', 'content', 'history'] as Tab[]).map((t) => (
            <button
              key={t}
              onClick={() => setTab(t)}
              className={`px-4 py-2 text-sm font-medium border-b-2 transition-colors ${
                tab === t
                  ? 'border-blue-600 text-blue-600'
                  : 'border-transparent text-gray-500 hover:text-gray-700'
              }`}
            >
              {t === 'chat' ? 'Chat' : t === 'content' ? 'Generate Konten' : 'Riwayat'}
            </button>
          ))}
        </div>

        {tab === 'chat' && <AiChat />}

        {tab === 'content' && (
          <div className="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <div className="lg:col-span-1 space-y-1">
              {GENERATORS.map((gen, i) => (
                <button
                  key={gen.feature}
                  onClick={() => setGeneratorIndex(i)}
                  className={`w-full text-left px-3 py-2 text-sm rounded-lg transition-colors ${
                    generatorIndex === i
                      ? 'bg-blue-50 text-blue-700 font-medium'
                      : 'text-gray-600 hover:bg-gray-100'
                  }`}
                >
                  {gen.title}
                </button>
              ))}
            </div>
            <div className="lg:col-span-3">
              <AiGenerator key={GENERATORS[generatorIndex].feature} {...GENERATORS[generatorIndex]} />
            </div>
          </div>
        )}

        {tab === 'history' && <AiHistory />}
      </div>
    </div>
  );
}
