import type { DashboardStatistics } from '@/types';

interface WeddingReadinessProps {
  statistics: DashboardStatistics;
}

interface ReadinessItem {
  label: string;
  current: number;
  total: number;
  unit: string;
}

export function WeddingReadiness({ statistics }: WeddingReadinessProps) {
  const checklistScore = statistics.checklist_progress;
  const budgetScore =
    statistics.budget_total > 0
      ? Math.min((statistics.budget_spent / statistics.budget_total) * 100, 100)
      : 0;
  const rsvpScore =
    statistics.guests_count > 0
      ? (statistics.rsvp_confirmed_count / statistics.guests_count) * 100
      : 0;

  const overallScore = Math.round(
    (checklistScore + budgetScore + rsvpScore) / 3
  );

  const items: ReadinessItem[] = [
    {
      label: 'Checklist',
      current: Math.round(checklistScore),
      total: 100,
      unit: '%',
    },
    {
      label: 'Budget',
      current: Math.round(budgetScore),
      total: 100,
      unit: '%',
    },
    {
      label: 'RSVPs',
      current: statistics.rsvp_confirmed_count,
      total: statistics.guests_count,
      unit: '',
    },
  ];

  return (
    <div className="rounded-lg border bg-card p-6 shadow-sm">
      <div className="flex items-center justify-between">
        <h3 className="text-lg font-semibold text-card-foreground">
          Wedding Readiness
        </h3>
        <span className="text-2xl font-bold text-primary">{overallScore}%</span>
      </div>
      <div className="mt-4 space-y-3">
        {items.map((item) => (
          <div key={item.label}>
            <div className="flex items-center justify-between text-sm">
              <span className="text-muted-foreground">{item.label}</span>
              <span className="font-medium text-card-foreground">
                {item.unit === '%'
                  ? `${item.current}%`
                  : `${item.current}/${item.total}`}
              </span>
            </div>
            <div className="mt-1 h-1.5 overflow-hidden rounded-full bg-muted">
              <div
                className="h-full rounded-full bg-primary transition-all"
                style={{
                  width: `${item.total > 0 ? (item.current / item.total) * 100 : 0}%`,
                }}
              />
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
