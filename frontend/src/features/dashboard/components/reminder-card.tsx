import type { Reminder as ReminderType } from '@/types';

interface ReminderCardProps {
  reminders: ReminderType[];
}

export function ReminderCard({ reminders }: ReminderCardProps) {
  if (reminders.length === 0) {
    return (
      <div className="rounded-lg border bg-card p-6 shadow-sm">
        <h3 className="text-lg font-semibold text-card-foreground">Reminders</h3>
        <p className="mt-4 text-sm text-muted-foreground">No upcoming reminders.</p>
      </div>
    );
  }

  return (
    <div className="rounded-lg border bg-card p-6 shadow-sm">
      <h3 className="text-lg font-semibold text-card-foreground">Reminders</h3>
      <div className="mt-4 space-y-3">
        {reminders.map((reminder) => (
          <div
            key={reminder.id}
            className="flex items-center justify-between rounded-md border p-3"
          >
            <div>
              <p className="text-sm font-medium text-card-foreground">{reminder.title}</p>
              <p className="text-xs text-muted-foreground">
                {new Date(reminder.date).toLocaleDateString()}
              </p>
            </div>
            <span className="rounded-full bg-primary/10 px-2 py-1 text-xs font-medium text-primary">
              {reminder.type}
            </span>
          </div>
        ))}
      </div>
    </div>
  );
}
