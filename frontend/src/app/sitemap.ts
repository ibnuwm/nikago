import type { MetadataRoute } from 'next';

const BASE_URL = 'https://nikago.com';

interface SitemapUrl {
  loc: string;
  priority?: string;
  changefreq?: string;
  lastmod?: string;
}

export default async function sitemap(): Promise<MetadataRoute.Sitemap> {
  const staticUrls: MetadataRoute.Sitemap = [
    {
      url: BASE_URL,
      lastModified: new Date(),
      changeFrequency: 'monthly',
      priority: 1,
    },
    {
      url: `${BASE_URL}/login`,
      lastModified: new Date(),
      changeFrequency: 'monthly',
      priority: 0.5,
    },
    {
      url: `${BASE_URL}/register`,
      lastModified: new Date(),
      changeFrequency: 'monthly',
      priority: 0.7,
    },
    {
      url: `${BASE_URL}/blog`,
      lastModified: new Date(),
      changeFrequency: 'weekly',
      priority: 0.9,
    },
  ];

  let dynamicUrls: MetadataRoute.Sitemap = [];

  try {
    const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000/api';
    const response = await fetch(`${apiUrl}/cms/sitemap`, {
      next: { revalidate: 3600 },
    });

    if (response.ok) {
      const body = await response.json();
      const urls = body.data as SitemapUrl[];

      dynamicUrls = urls
        .filter((u: SitemapUrl) => u.loc !== '/')
        .map((u: SitemapUrl) => ({
          url: `${BASE_URL}${u.loc}`,
          lastModified: u.lastmod ? new Date(u.lastmod) : new Date(),
          changeFrequency: (u.changefreq as MetadataRoute.Sitemap[number]['changeFrequency']) ?? 'monthly',
          priority: u.priority ? parseFloat(u.priority) : 0.5,
        }));
    }
  } catch {
    // API not available during build, return static URLs only
  }

  return [...staticUrls, ...dynamicUrls];
}
