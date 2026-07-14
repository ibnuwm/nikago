import type { RecentActivity as RecentActivityType } from '@/types';

interface RecentActivityProps {
  activities: RecentActivityType[];
}

export function RecentActivity({ activities }: RecentActivityProps) {
  if (activities.length === 0) {
    return (
      <div className="rounded-lg border bg-card p-6 shadow-sm">
        <h3 className="text-lg font-semibold text-card-foreground">Recent Activity</h3>
        <p className="mt-4 text-sm text-muted-foreground">No recent activity yet.</p>
      </div>
    );
  }

  return (
    <div className="rounded-lg border bg-card p-6 shadow-sm">
      <h3 className="text-lg font-semibold text-card-foreground">Recent Activity</h3>
      <div className="mt-4 space-y-4">
        {activities.map((activity) => (
          <div key={activity.id} className="flex items-start gap-3">
            <div className="mt-1 h-2 w-2 shrink-0 rounded-full bg-primary" />
            <div className="min-w-0 flex-1">
              <p className="text-sm font-medium text-card-foreground">{activity.title}</p>
              {activity.description && (
                <p className="text-xs text-muted-foreground">{activity.description}</p>
              )}
              <p className="mt-1 text-xs text-muted-foreground">
                {new Date(activity.created_at).toLocaleDateString()}
              </p>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}
