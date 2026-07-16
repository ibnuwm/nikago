'use client';

import type { Timeline } from '@/types';

interface CalendarViewProps {
  timelines: Timeline[];
}

function getCalendarDays(year: number, month: number): (number | null)[] {
  const firstDay = new Date(year, month, 1).getDay();
  const daysInMonth = new Date(year, month + 1, 0).getDate();
  const days: (number | null)[] = [];
  for (let i = 0; i < firstDay; i++) days.push(null);
  for (let d = 1; d <= daysInMonth; d++) days.push(d);
  return days;
}

const MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
const DAY_NAMES = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

export function CalendarView({ timelines }: CalendarViewProps) {
  const today = new Date();
  const year = today.getFullYear();
  const month = today.getMonth();
  const days = getCalendarDays(year, month);

  const taskDates = new Map<string, { title: string; priority: string }[]>();
  timelines.forEach((tl) => {
    tl.tasks?.forEach((t) => {
      if (t.due_date) {
        const existing = taskDates.get(t.due_date) || [];
        existing.push({ title: t.title, priority: t.priority });
        taskDates.set(t.due_date, existing);
      }
    });
  });

  return (
    <div className="rounded-xl border bg-card">
      <div className="border-b px-6 py-4">
        <h3 className="text-lg font-semibold">{MONTHS[month]} {year}</h3>
      </div>
      <div className="p-4">
        <div className="grid grid-cols-7 gap-1 mb-1">
          {DAY_NAMES.map((d) => (
            <div key={d} className="text-center text-xs font-medium text-muted-foreground py-1">{d}</div>
          ))}
        </div>
        <div className="grid grid-cols-7 gap-1">
          {days.map((day, i) => {
            if (!day) return <div key={`empty-${i}`} />;
            const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const tasksOnDay = taskDates.get(dateStr) || [];
            const isSunday = new Date(year, month, day).getDay() === 0;

            return (
              <div
                key={dateStr}
                className={`min-h-[72px] rounded-lg border p-1 ${
                  isSunday ? 'bg-muted/30' : ''
                } ${tasksOnDay.length > 0 ? 'border-primary/30' : ''}`}
              >
                <span className={`text-xs font-medium ${isSunday ? 'text-muted-foreground' : ''}`}>
                  {day}
                </span>
                {tasksOnDay.slice(0, 2).map((t, j) => (
                  <p key={j} className="text-[10px] leading-tight truncate mt-0.5 text-muted-foreground">
                    {t.title}
                  </p>
                ))}
                {tasksOnDay.length > 2 && (
                  <p className="text-[10px] text-primary mt-0.5">+{tasksOnDay.length - 2} more</p>
                )}
              </div>
            );
          })}
        </div>
      </div>
    </div>
  );
}
