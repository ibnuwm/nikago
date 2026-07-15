import type { PlannerProgress } from '@/types';

interface ProgressCardProps {
  progress: PlannerProgress;
}

export function ProgressCard({ progress }: ProgressCardProps) {
  return (
    <div className="rounded-lg border bg-card p-6 shadow-sm">
      <h3 className="text-lg font-semibold text-card-foreground">Preparation Progress</h3>
      <div className="mt-4">
        <div className="flex items-center justify-between text-sm">
          <span className="text-muted-foreground">Overall completion</span>
          <span className="font-medium text-card-foreground">{progress.progress}%</span>
        </div>
        <div className="mt-3 h-3 overflow-hidden rounded-full bg-muted">
          <div
            className="h-full rounded-full bg-primary transition-all"
            style={{ width: `${Math.min(progress.progress, 100)}%` }}
          />
        </div>
        <p className="mt-2 text-xs text-muted-foreground">
          {progress.completed_task} of {progress.total_task} tasks completed
        </p>
      </div>
    </div>
  );
}
