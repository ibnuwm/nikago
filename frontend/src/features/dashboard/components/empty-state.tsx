import { Heart } from 'lucide-react';
import Link from 'next/link';

interface EmptyStateProps {
  userName: string;
}

export function EmptyState({ userName }: EmptyStateProps) {
  return (
    <div className="flex flex-col items-center justify-center rounded-lg border border-dashed bg-card px-8 py-16 text-center shadow-sm">
      <div className="flex h-16 w-16 items-center justify-center rounded-full bg-primary/10">
        <Heart className="h-8 w-8 text-primary" />
      </div>
      <h2 className="mt-6 text-xl font-bold text-card-foreground">
        Welcome, {userName}!
      </h2>
      <p className="mt-2 max-w-md text-sm text-muted-foreground">
        Start planning your dream wedding. Create your first wedding to unlock
        guest management, budget tracking, RSVPs, and more.
      </p>
      <Link
        href="/weddings/create"
        className="mt-8 inline-flex h-10 items-center justify-center rounded-md bg-primary px-8 text-sm font-medium text-primary-foreground shadow-sm transition-colors hover:bg-primary/90"
      >
        Create My Wedding
      </Link>
    </div>
  );
}
