'use client';

import type { Lead, CrmStatistics } from '@/types';

interface CrmStatisticsProps {
  statistics: CrmStatistics;
}

function formatCurrency(amount: number): string {
  return `Rp${amount.toLocaleString('id-ID')}`;
}

export function CrmStatisticsCard({ statistics }: CrmStatisticsProps) {
  const items = [
    { label: 'Total Lead', value: statistics.total_leads, color: 'text-blue-600' },
    { label: 'Active', value: statistics.active, color: 'text-purple-600' },
    { label: 'Won', value: statistics.won, color: 'text-green-600' },
    { label: 'Lost', value: statistics.lost, color: 'text-red-600' },
    { label: 'Total Value', value: formatCurrency(statistics.total_value), color: 'text-card-foreground' },
    { label: 'Won Value', value: formatCurrency(statistics.won_value), color: 'text-green-600' },
    { label: 'Conversion Rate', value: `${statistics.conversion_rate}%`, color: 'text-card-foreground' },
  ];

  return (
    <div className="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
      {items.map((item) => (
        <div key={item.label} className="rounded-lg border bg-card p-4">
          <p className="text-xs text-muted-foreground">{item.label}</p>
          <p className={`mt-1 text-lg font-bold ${item.color}`}>{item.value}</p>
        </div>
      ))}
    </div>
  );
}
