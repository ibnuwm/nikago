import Link from 'next/link';
import { Calendar, Users, ListChecks, CreditCard } from 'lucide-react';

const actions = [
  {
    label: 'Guest List',
    href: '/guests',
    icon: Users,
    description: 'Manage your guests',
  },
  {
    label: 'Timeline',
    href: '/timeline',
    icon: Calendar,
    description: 'Plan your schedule',
  },
  {
    label: 'Checklist',
    href: '/checklist',
    icon: ListChecks,
    description: 'Track tasks',
  },
  {
    label: 'Budget',
    href: '/budget',
    icon: CreditCard,
    description: 'Manage expenses',
  },
];

export function QuickActions() {
  return (
    <div className="rounded-lg border bg-card p-6 shadow-sm">
      <h3 className="text-lg font-semibold text-card-foreground">
        Quick Actions
      </h3>
      <div className="mt-4 grid grid-cols-2 gap-3">
        {actions.map((action) => (
          <Link
            key={action.href}
            href={action.href}
            className="flex items-center gap-3 rounded-md border p-3 text-left transition-colors hover:bg-muted"
          >
            <action.icon className="h-5 w-5 shrink-0 text-primary" />
            <div className="min-w-0">
              <p className="text-sm font-medium text-card-foreground">
                {action.label}
              </p>
              <p className="text-xs text-muted-foreground">
                {action.description}
              </p>
            </div>
          </Link>
        ))}
      </div>
    </div>
  );
}
