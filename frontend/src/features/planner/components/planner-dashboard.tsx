import type { PlannerData } from '@/types';
import { ProgressCard } from './progress-card';
import { SummaryCard } from './summary-card';

interface PlannerDashboardProps {
  data: PlannerData;
}

export function PlannerDashboard({ data }: PlannerDashboardProps) {
  const { progress, summary } = data;

  return (
    <>
      <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <SummaryCard
          title="Total Tasks"
          value={summary.total_task ?? 0}
          description="Across all checklists"
        />
        <SummaryCard
          title="Completed"
          value={summary.completed_task ?? 0}
          description={`${summary.progress ?? 0}% done`}
        />
        <SummaryCard
          title="Budget"
          value={`Rp ${(summary.budget_spent ?? 0).toLocaleString()}`}
          description={`of Rp ${(summary.budget_total ?? 0).toLocaleString()}`}
        />
        <SummaryCard
          title="Guests"
          value={summary.guests_count ?? 0}
          description="Total invited"
        />
      </div>

      <div className="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
        <ProgressCard progress={progress} />
        <div className="rounded-lg border bg-card p-6 shadow-sm">
          <h3 className="text-lg font-semibold text-card-foreground">Quick Overview</h3>
          <div className="mt-4 space-y-3">
            <div className="flex items-center justify-between text-sm">
              <span className="text-muted-foreground">Checklists</span>
              <span className="font-medium text-card-foreground">{summary.checklist_count}</span>
            </div>
            <div className="flex items-center justify-between text-sm">
              <span className="text-muted-foreground">Timeline items</span>
              <span className="font-medium text-card-foreground">{summary.timeline_count}</span>
            </div>
            <div className="flex items-center justify-between text-sm">
              <span className="text-muted-foreground">Reminders</span>
              <span className="font-medium text-card-foreground">{summary.reminder_count}</span>
            </div>
          </div>
        </div>
      </div>
    </>
  );
}
