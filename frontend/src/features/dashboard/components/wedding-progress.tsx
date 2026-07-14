interface WeddingProgressProps {
  daysRemaining: number | null;
  weddingDate: string | null;
}

export function WeddingProgress({ daysRemaining, weddingDate }: WeddingProgressProps) {
  if (!weddingDate) {
    return (
      <div className="rounded-lg border bg-card p-6 shadow-sm">
        <h3 className="text-lg font-semibold text-card-foreground">Wedding Progress</h3>
        <p className="mt-4 text-sm text-muted-foreground">
          No wedding date set yet. Create your wedding to get started.
        </p>
      </div>
    );
  }

  const totalDays = 365;
  const elapsed = daysRemaining !== null ? totalDays - daysRemaining : totalDays;
  const progress = Math.min(Math.max((elapsed / totalDays) * 100, 0), 100);

  return (
    <div className="rounded-lg border bg-card p-6 shadow-sm">
      <h3 className="text-lg font-semibold text-card-foreground">Wedding Progress</h3>
      <div className="mt-4">
        <div className="flex items-center justify-between text-sm">
          <span className="text-muted-foreground">
            {daysRemaining !== null ? `${daysRemaining} days remaining` : 'Loading...'}
          </span>
          <span className="font-medium text-card-foreground">
            {new Date(weddingDate).toLocaleDateString()}
          </span>
        </div>
        <div className="mt-3 h-2 overflow-hidden rounded-full bg-muted">
          <div
            className="h-full rounded-full bg-primary transition-all"
            style={{ width: `${progress}%` }}
          />
        </div>
        <p className="mt-2 text-right text-xs text-muted-foreground">
          {Math.round(progress)}% complete
        </p>
      </div>
    </div>
  );
}
