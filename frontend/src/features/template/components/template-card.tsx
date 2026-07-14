import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';
import type { InvitationTemplate } from '@/types';

interface TemplateCardProps {
  template: InvitationTemplate;
  onUse?: (uuid: string) => void;
  onFavorite?: (uuid: string) => void;
  onUnfavorite?: (uuid: string) => void;
  isUsing?: boolean;
}

export function TemplateCard({
  template,
  onUse,
  onFavorite,
  onUnfavorite,
  isUsing,
}: TemplateCardProps) {
  return (
    <div className="rounded-lg border bg-card p-4 shadow-sm">
      <div className="aspect-[3/4] overflow-hidden rounded-md bg-muted">
        {template.image ? (
          <img
            src={template.image}
            alt={template.name}
            className="h-full w-full object-cover"
          />
        ) : (
          <div className="flex h-full items-center justify-center text-sm text-muted-foreground">
            No preview
          </div>
        )}
      </div>

      <div className="mt-3">
        <div className="flex items-start justify-between">
          <h3 className="text-sm font-semibold text-card-foreground">
            {template.name}
          </h3>
          {template.is_premium && (
            <span className="inline-flex items-center rounded-full bg-yellow-100 px-2 py-0.5 text-xs font-medium text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
              Premium
            </span>
          )}
        </div>

        <p className="mt-1 text-xs text-muted-foreground capitalize">
          {template.category}
        </p>

        <div className="mt-2 flex items-center gap-2 text-xs text-muted-foreground">
          <span>{template.favorites_count} favorites</span>
        </div>
      </div>

      <div className="mt-3 flex gap-2">
        {onUse && (
          <Button
            variant="outline"
            size="sm"
            className="flex-1"
            onClick={() => onUse(template.id)}
            disabled={isUsing}
          >
            {isUsing ? 'Using...' : 'Use'}
          </Button>
        )}
        {template.is_favorited ? (
          onUnfavorite && (
            <Button
              variant="outline"
              size="sm"
              onClick={() => onUnfavorite(template.id)}
            >
              Unfavorite
            </Button>
          )
        ) : (
          onFavorite && (
            <Button
              variant="outline"
              size="sm"
              onClick={() => onFavorite(template.id)}
            >
              Favorite
            </Button>
          )
        )}
      </div>
    </div>
  );
}
