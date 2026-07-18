'use client';

interface TrendItem {
  date: string;
  count: number;
}

interface TrendChartProps {
  data: TrendItem[];
  loading?: boolean;
  maxBars?: number;
}

export function AnalyticsTrendChart({ data, loading, maxBars = 14 }: TrendChartProps) {
  if (loading) {
    return (
      <div className="bg-white rounded-lg border p-4 animate-pulse">
        <div className="h-4 bg-gray-200 rounded w-24 mb-4" />
        <div className="space-y-2">
          {Array.from({ length: 7 }).map((_, i) => (
            <div key={i} className="h-6 bg-gray-200 rounded" />
          ))}
        </div>
      </div>
    );
  }

  const sliced = data.slice(-maxBars);
  const maxCount = Math.max(...sliced.map((d) => d.count), 1);

  return (
    <div className="bg-white rounded-lg border p-4">
      <p className="text-sm font-medium text-gray-700 mb-3">Trend</p>
      <div className="space-y-1.5">
        {sliced.map((item) => (
          <div key={item.date} className="flex items-center gap-2">
            <span className="text-xs text-gray-500 w-24 shrink-0">
              {new Date(item.date).toLocaleDateString('id-ID', { weekday: 'short', month: 'short', day: 'numeric' })}
            </span>
            <div className="flex-1 h-5 bg-gray-100 rounded overflow-hidden">
              <div
                className="h-full bg-blue-500 rounded transition-all"
                style={{ width: `${(item.count / maxCount) * 100}%` }}
              />
            </div>
            <span className="text-xs text-gray-600 w-12 text-right">{item.count}</span>
          </div>
        ))}
      </div>
    </div>
  );
}
