import type { Metadata } from 'next';
import Link from 'next/link';
import { notFound } from 'next/navigation';

const BASE_URL = 'https://nikago.com';

interface BlogPostData {
  id: string;
  title: string;
  slug: string;
  excerpt: string | null;
  content: string | null;
  featured_image: string | null;
  author: { id: string; name: string; avatar: string | null } | null;
  category: { id: string; name: string; slug: string } | null;
  tags: { id: string; name: string; slug: string }[];
  status: string;
  published_at: string | null;
  seo_title: string | null;
  seo_description: string | null;
  created_at: string;
  updated_at: string;
}

async function fetchPost(slug: string): Promise<BlogPostData | null> {
  try {
    const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api';
    const response = await fetch(`${apiUrl}/cms/blog/posts/${slug}`, {
      next: { revalidate: 300 },
    });

    if (!response.ok) return null;

    const body = await response.json();
    return body.data as BlogPostData;
  } catch {
    return null;
  }
}

export async function generateMetadata({
  params,
}: {
  params: Promise<{ slug: string }>;
}): Promise<Metadata> {
  const { slug } = await params;
  const post = await fetchPost(slug);

  if (!post) {
    return {
      title: 'Article Not Found',
    };
  }

  const title = post.seo_title || post.title;
  const description = post.seo_description || post.excerpt || '';
  const url = `${BASE_URL}/blog/${post.slug}`;

  return {
    title,
    description,
    alternates: { canonical: url },
    openGraph: {
      type: 'article',
      url,
      title,
      description,
      siteName: 'Nikago',
      publishedTime: post.published_at ?? undefined,
      authors: post.author ? [post.author.name] : undefined,
      images: post.featured_image
        ? [{ url: post.featured_image, width: 1200, height: 630, alt: post.title }]
        : undefined,
    },
    twitter: {
      card: 'summary_large_image',
      title,
      description,
      images: post.featured_image ? [post.featured_image] : undefined,
    },
  };
}

export default async function BlogPostPage({
  params,
}: {
  params: Promise<{ slug: string }>;
}) {
  const { slug } = await params;
  const post = await fetchPost(slug);

  if (!post) {
    notFound();
  }

  const blogPostingJsonLd = {
    '@context': 'https://schema.org',
    '@type': 'BlogPosting',
    headline: post.title,
    description: post.excerpt || post.seo_description || '',
    image: post.featured_image || undefined,
    datePublished: post.published_at || undefined,
    dateModified: post.updated_at || undefined,
    author: post.author
      ? { '@type': 'Person', name: post.author.name }
      : undefined,
    publisher: {
      '@type': 'Organization',
      name: 'Nikago',
      url: BASE_URL,
    },
    mainEntityOfPage: {
      '@type': 'WebPage',
      '@id': `${BASE_URL}/blog/${post.slug}`,
    },
  };

  return (
    <div className="min-h-screen bg-background">
      <script
        type="application/ld+json"
        dangerouslySetInnerHTML={{ __html: JSON.stringify(blogPostingJsonLd) }}
      />
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
