'use client';

import { useParams } from 'next/navigation';
import Link from 'next/link';
import { useBlogPost } from '@/features/cms/hooks/use-blog';

export default function BlogPostPage() {
  const params = useParams();
  const slug = params.slug as string;
  const { data: post, isLoading, error } = useBlogPost(slug);

  if (isLoading) {
    return (
      <div className="min-h-screen bg-background">
        <div className="mx-auto max-w-3xl px-6 py-16 sm:py-24">
          <div className="animate-pulse space-y-6">
            <div className="h-4 w-1/3 rounded bg-muted" />
            <div className="h-10 w-3/4 rounded bg-muted" />
            <div className="aspect-[16/9] rounded-lg bg-muted" />
            <div className="space-y-3">
              <div className="h-4 w-full rounded bg-muted" />
              <div className="h-4 w-5/6 rounded bg-muted" />
              <div className="h-4 w-4/6 rounded bg-muted" />
            </div>
          </div>
        </div>
      </div>
    );
  }

  if (error || !post) {
    return (
      <div className="min-h-screen bg-background">
        <div className="mx-auto max-w-3xl px-6 py-16 sm:py-24 text-center">
          <h1 className="text-2xl font-bold text-foreground">Article Not Found</h1>
          <p className="mt-4 text-muted-foreground">
            The article you&apos;re looking for doesn&apos;t exist or has been removed.
          </p>
          <Link
            href="/blog"
            className="mt-8 inline-flex items-center text-sm font-medium text-primary hover:underline"
          >
            &larr; Back to Blog
          </Link>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen bg-background">
      <article className="mx-auto max-w-3xl px-6 py-16 sm:py-24">
        <Link
          href="/blog"
          className="inline-flex items-center text-sm font-medium text-muted-foreground hover:text-foreground"
        >
          &larr; Back to Blog
        </Link>

        <div className="mt-8">
          {post.category && (
            <span className="text-sm font-medium text-primary">
              {post.category.name}
            </span>
          )}
          <h1 className="mt-2 text-3xl font-bold tracking-tight text-foreground sm:text-4xl">
            {post.title}
          </h1>

          <div className="mt-4 flex items-center gap-3 text-sm text-muted-foreground">
            {post.author && (
              <span>By {post.author.name}</span>
            )}
            {post.published_at && (
              <>
                <span>&middot;</span>
                <time dateTime={post.published_at}>
                  {new Date(post.published_at).toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                  })}
                </time>
              </>
            )}
          </div>
        </div>

        {post.featured_image && (
          <div className="mt-8 overflow-hidden rounded-lg">
            <img
              src={post.featured_image}
              alt={post.title}
              className="w-full object-cover"
            />
          </div>
        )}

        {post.content && (
          <div
            className="prose prose-gray mt-10 max-w-none dark:prose-invert"
            dangerouslySetInnerHTML={{ __html: post.content }}
          />
        )}

        {post.tags && post.tags.length > 0 && (
          <div className="mt-12 flex flex-wrap gap-2">
            {post.tags.map((tag) => (
              <Link
                key={tag.id}
                href={`/blog?tag=${tag.slug}`}
                className="rounded-full bg-muted px-3 py-1 text-xs font-medium text-muted-foreground hover:bg-muted/80"
              >
                #{tag.name}
              </Link>
            ))}
          </div>
        )}
      </article>
    </div>
  );
}
