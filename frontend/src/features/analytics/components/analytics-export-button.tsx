'use client';

import { useState } from 'react';
import api from '@/services/api';
import type { ApiResponse, AnalyticsExport } from '@/types';

interface ExportButtonProps {
  type?: string;
  filters?: { start_date?: string; end_date?: string };
}

export function AnalyticsExportButton({ type = 'dashboard', filters }: ExportButtonProps) {
  const [loading, setLoading] = useState(false);

  const handleExport = async () => {
    setLoading(true);
    try {
      const params = new URLSearchParams();
      params.set('type', type);
      params.set('format', 'csv');
      if (filters?.start_date) params.set('start_date', filters.start_date);
      if (filters?.end_date) params.set('end_date', filters.end_date);

      const response = await api.get<ApiResponse<AnalyticsExport>>('/analytics/export', { params });
      const data = response.data.data;

      const csvContent = [
        data.headers.join(','),
        ...data.data.map((row) => row.join(',')),
      ].join('\n');

      const blob = new Blob([csvContent], { type: 'text/csv' });
      const url = URL.createObjectURL(blob);
      const a = document.createElement('a');
      a.href = url;
      a.download = `analytics-${type}-${new Date().toISOString().split('T')[0]}.csv`;
      a.click();
      URL.revokeObjectURL(url);
    } catch {
      // Silently fail
    } finally {
      setLoading(false);
    }
  };

  return (
    <button
      onClick={handleExport}
      disabled={loading}
      className="px-3 py-1.5 border text-sm rounded hover:bg-gray-50 disabled:opacity-50"
    >
      {loading ? 'Exporting...' : 'Export CSV'}
    </button>
  );
}
