'use client';

import { useState } from 'react';
import Link from 'next/link';
import { Input } from '@/components/ui/input';
import { useBlogCategories, useBlogPosts, useBlogTags } from '@/features/cms/hooks/use-blog';
import { cn } from '@/lib/utils';

export default function BlogPage() {
  const [search, setSearch] = useState('');
  const [categorySlug, setCategorySlug] = useState('');
  const [tagSlug, setTagSlug] = useState('');
  const [page, setPage] = useState(1);

  const { data: postsData, isLoading: postsLoading } = useBlogPosts({
    search: search || undefined,
    category: categorySlug || undefined,
    tag: tagSlug || undefined,
    page,
    per_page: 9,
  });

  const { data: categories } = useBlogCategories();
  const { data: tags } = useBlogTags();

  const posts = postsData?.data ?? [];
  const meta = postsData?.meta;
  const totalPages = meta?.last_page ?? 1;

  return (
    <div className="min-h-screen bg-background">
      <div className="mx-auto max-w-7xl px-6 py-16 sm:py-24 lg:px-8">
        <div className="text-center">
          <h1 className="text-4xl font-bold tracking-tight text-foreground sm:text-5xl">
            Blog
          </h1>
          <p className="mt-4 text-lg text-muted-foreground">
            Wedding tips, inspiration, and stories to help you plan your perfect day.
          </p>
        </div>

        <div className="mt-8 flex justify-center">
          <Input
            type="search"
            placeholder="Search articles..."
            value={search}
            onChange={(e) => { setSearch(e.target.value); setPage(1); }}
            className="max-w-md"
          />
        </div>

        {categories && categories.length > 0 && (
          <div className="mt-8 flex flex-wrap justify-center gap-2">
            <button
              onClick={() => { setCategorySlug(''); setTagSlug(''); setPage(1); }}
              className={cn(
                'rounded-full px-4 py-1.5 text-sm font-medium transition-colors',
                !categorySlug && !tagSlug
                  ? 'bg-primary text-primary-foreground'
                  : 'bg-muted text-muted-foreground hover:bg-muted/80'
              )}
            >
              All
            </button>
            {categories.map((cat) => (
              <button
                key={cat.id}
                onClick={() => { setCategorySlug(cat.slug); setTagSlug(''); setPage(1); }}
                className={cn(
                  'rounded-full px-4 py-1.5 text-sm font-medium transition-colors',
                  categorySlug === cat.slug
                    ? 'bg-primary text-primary-foreground'
                    : 'bg-muted text-muted-foreground hover:bg-muted/80'
                )}
              >
                {cat.name}
              </button>
            ))}
          </div>
        )}

        {tags && tags.length > 0 && (
          <div className="mt-4 flex flex-wrap justify-center gap-2">
            {tags.map((t) => (
              <button
                key={t.id}
                onClick={() => { setTagSlug(tagSlug === t.slug ? '' : t.slug); setCategorySlug(''); setPage(1); }}
                className={cn(
                  'rounded-full px-3 py-1 text-xs font-medium transition-colors',
                  tagSlug === t.slug
                    ? 'bg-secondary text-secondary-foreground'
                    : 'bg-muted/50 text-muted-foreground hover:bg-muted/80'
                )}
              >
                #{t.name}
              </button>
            ))}
          </div>
        )}

        <div className="mt-12">
          {postsLoading ? (
            <div className="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
              {[...Array(6)].map((_, i) => (
                <div key={i} className="animate-pulse rounded-lg border bg-card">
                  <div className="aspect-[16/9] rounded-t-lg bg-muted" />
                  <div className="space-y-3 p-6">
                    <div className="h-4 w-1/4 rounded bg-muted" />
                    <div className="h-5 w-3/4 rounded bg-muted" />
                    <div className="h-4 w-full rounded bg-muted" />
                    <div className="h-4 w-2/3 rounded bg-muted" />
                  </div>
                </div>
              ))}
            </div>
          ) : posts.length > 0 ? (
            <>
              <div className="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                {posts.map((post) => (
                  <Link
                    key={post.id}
                    href={`/blog/${post.slug}`}
                    className="group rounded-lg border bg-card transition-shadow hover:shadow-md"
                  >
                    {post.featured_image && (
                      <div className="aspect-[16/9] overflow-hidden rounded-t-lg">
                        <img
                          src={post.featured_image}
                          alt={post.title}
                          className="h-full w-full object-cover transition-transform group-hover:scale-105"
                        />
                      </div>
                    )}
                    <div className="p-6">
                      {post.category && (
                        <span className="text-xs font-medium text-primary">
                          {post.category.name}
                        </span>
                      )}
                      <h2 className="mt-2 text-lg font-semibold text-card-foreground group-hover:text-primary">
                        {post.title}
                      </h2>
                      {post.excerpt && (
                        <p className="mt-2 line-clamp-2 text-sm text-muted-foreground">
                          {post.excerpt}
                        </p>
                      )}
                      <div className="mt-4 flex items-center gap-3 text-xs text-muted-foreground">
                        {post.author && (
                          <span>{post.author.name}</span>
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
                  </Link>
                ))}
              </div>

              {totalPages > 1 && (
                <div className="mt-12 flex justify-center gap-2">
                  {Array.from({ length: totalPages }, (_, i) => i + 1).map((p) => (
                    <button
                      key={p}
                      onClick={() => setPage(p)}
                      className={cn(
                        'flex h-10 w-10 items-center justify-center rounded-lg text-sm font-medium transition-colors',
                        p === page
                          ? 'bg-primary text-primary-foreground'
                          : 'bg-muted text-muted-foreground hover:bg-muted/80'
                      )}
                    >
                      {p}
                    </button>
                  ))}
                </div>
              )}
            </>
          ) : (
            <div className="rounded-lg border bg-card p-12 text-center">
              <p className="text-muted-foreground">No articles found.</p>
            </div>
          )}
        </div>
      </div>
    </div>
  );
}
