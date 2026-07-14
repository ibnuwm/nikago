import Link from 'next/link';
import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';
import type { Wedding } from '@/types';

interface WeddingCardProps {
  wedding: Wedding;
  onPublish?: (uuid: string) => void;
  onArchive?: (uuid: string) => void;
  onDelete?: (uuid: string) => void;
  isPublishing?: boolean;
  isArchiving?: boolean;
  isDeleting?: boolean;
}

export function WeddingCard({
  wedding,
  onPublish,
  onArchive,
  onDelete,
  isPublishing,
  isArchiving,
  isDeleting,
}: WeddingCardProps) {
  return (
    <div className="rounded-lg border bg-card p-6 shadow-sm">
      <div className="flex items-start justify-between">
        <div className="flex-1">
          <h3 className="text-lg font-semibold text-card-foreground">
            {wedding.title}
          </h3>
          <p className="mt-1 text-sm text-muted-foreground">
            {new Date(wedding.created_at).toLocaleDateString()}
          </p>
        </div>
        <StatusBadge status={wedding.status} />
      </div>

      {wedding.theme && (
        <p className="mt-2 text-sm text-muted-foreground">
          Theme: {wedding.theme}
        </p>
      )}

      <div className="mt-4 flex flex-wrap gap-2">
        <Link href={`/weddings/${wedding.id}`}>
          <Button variant="outline" size="sm">
            View
          </Button>
        </Link>
        <Link href={`/weddings/${wedding.id}/edit`}>
          <Button variant="outline" size="sm">
            Edit
          </Button>
        </Link>
        {wedding.status === 'draft' && onPublish && (
          <Button
            variant="outline"
            size="sm"
            onClick={() => onPublish(wedding.id)}
            disabled={isPublishing}
          >
            {isPublishing ? 'Publishing...' : 'Publish'}
          </Button>
        )}
        {wedding.status === 'published' && onArchive && (
          <Button
            variant="outline"
            size="sm"
            onClick={() => onArchive(wedding.id)}
            disabled={isArchiving}
          >
            {isArchiving ? 'Archiving...' : 'Archive'}
          </Button>
        )}
        {onDelete && (
          <Button
            variant="destructive"
            size="sm"
            onClick={() => onDelete(wedding.id)}
            disabled={isDeleting}
          >
            {isDeleting ? 'Deleting...' : 'Delete'}
          </Button>
        )}
      </div>
    </div>
  );
}

function StatusBadge({ status }: { status: string }) {
  const variants: Record<string, string> = {
    draft: 'bg-muted text-muted-foreground',
    published: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
    archived: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
  };

  return (
    <span
      className={cn(
        'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium capitalize',
        variants[status] || variants.draft
      )}
    >
      {status}
    </span>
  );
}
