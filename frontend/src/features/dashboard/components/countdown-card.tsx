interface CountdownCardProps {
  daysRemaining: number;
  hoursRemaining: number;
  weddingDate: string;
  phase: string;
}

export function CountdownCard({
  daysRemaining,
  hoursRemaining,
  weddingDate,
  phase,
}: CountdownCardProps) {
  const formattedDate = new Date(weddingDate).toLocaleDateString('en-US', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });

  return (
    <div className="rounded-lg border bg-gradient-to-br from-primary/5 to-primary/10 p-6 shadow-sm">
      <p className="text-sm font-medium text-primary">{phase}</p>
      <div className="mt-3 flex items-baseline gap-2">
        <span className="text-4xl font-bold tabular-nums text-card-foreground">
          {daysRemaining}
        </span>
        <span className="text-sm text-muted-foreground">
          days to go
        </span>
      </div>
      <div className="mt-2 flex gap-4 text-xs text-muted-foreground">
        <span>{hoursRemaining.toLocaleString()} hours</span>
        <span>&middot;</span>
        <span>{formattedDate}</span>
      </div>
    </div>
  );
}
