'use client';

interface SummaryCardProps {
  title: string;
  value: string | number;
  subtitle?: string;
  trend?: number;
  loading?: boolean;
}

export function AnalyticsSummaryCard({ title, value, subtitle, trend, loading }: SummaryCardProps) {
  if (loading) {
    return (
      <div className="bg-white rounded-lg border p-4 animate-pulse">
        <div className="h-4 bg-gray-200 rounded w-24 mb-2" />
        <div className="h-8 bg-gray-200 rounded w-32 mb-1" />
        <div className="h-3 bg-gray-200 rounded w-20" />
      </div>
    );
  }

  return (
    <div className="bg-white rounded-lg border p-4">
      <p className="text-sm text-gray-500 mb-1">{title}</p>
      <p className="text-2xl font-bold text-gray-900">
        {typeof value === 'number' ? value.toLocaleString() : value}
      </p>
      {subtitle && <p className="text-xs text-gray-400 mt-1">{subtitle}</p>}
      {trend !== undefined && (
        <p className={`text-xs mt-1 ${trend >= 0 ? 'text-green-600' : 'text-red-600'}`}>
          {trend >= 0 ? '+' : ''}{trend}% growth
        </p>
      )}
    </div>
  );
}
