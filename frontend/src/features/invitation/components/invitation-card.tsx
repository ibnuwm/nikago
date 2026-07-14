import Link from 'next/link';
import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';
import type { Invitation } from '@/types';

interface InvitationCardProps {
  invitation: Invitation;
  onPublish?: (uuid: string) => void;
  onDraft?: (uuid: string) => void;
  onDuplicate?: (uuid: string) => void;
  onDelete?: (uuid: string) => void;
  isPublishing?: boolean;
  isDrafting?: boolean;
  isDuplicating?: boolean;
  isDeleting?: boolean;
}

export function InvitationCard({
  invitation,
  onPublish,
  onDraft,
  onDuplicate,
  onDelete,
  isPublishing,
  isDrafting,
  isDuplicating,
  isDeleting,
}: InvitationCardProps) {
  return (
    <div className="rounded-lg border bg-card p-6 shadow-sm">
      <div className="flex items-start justify-between">
        <div className="flex-1">
          <h3 className="text-lg font-semibold text-card-foreground">
            {invitation.title}
          </h3>
          <p className="mt-1 text-sm text-muted-foreground">
            {new Date(invitation.created_at).toLocaleDateString()}
          </p>
        </div>
        <StatusBadge status={invitation.status} />
      </div>

      {invitation.description && (
        <p className="mt-2 text-sm text-muted-foreground line-clamp-2">
          {invitation.description}
        </p>
      )}

      <div className="mt-4 flex flex-wrap gap-2">
        <Link href={`/invitations/${invitation.id}`}>
          <Button variant="outline" size="sm">
            View
          </Button>
        </Link>
        <Link href={`/invitations/${invitation.id}/edit`}>
          <Button variant="outline" size="sm">
            Edit
          </Button>
        </Link>
        {invitation.status === 'draft' && onPublish && (
          <Button
            variant="outline"
            size="sm"
            onClick={() => onPublish(invitation.id)}
            disabled={isPublishing}
          >
            {isPublishing ? 'Publishing...' : 'Publish'}
          </Button>
        )}
        {invitation.status === 'published' && onDraft && (
          <Button
            variant="outline"
            size="sm"
            onClick={() => onDraft(invitation.id)}
            disabled={isDrafting}
          >
            {isDrafting ? 'Unpublishing...' : 'Unpublish'}
          </Button>
        )}
        {onDuplicate && (
          <Button
            variant="outline"
            size="sm"
            onClick={() => onDuplicate(invitation.id)}
            disabled={isDuplicating}
          >
            {isDuplicating ? 'Duplicating...' : 'Duplicate'}
          </Button>
        )}
        {onDelete && (
          <Button
            variant="destructive"
            size="sm"
            onClick={() => onDelete(invitation.id)}
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
