import type { MetadataRoute } from 'next';

export default function robots(): MetadataRoute.Robots {
  return {
    rules: [
      {
        userAgent: '*',
        allow: '/',
        disallow: [
          '/api/',
          '/dashboard',
          '/bookings',
          '/checklist',
          '/guests',
          '/invitations',
          '/marketplace',
          '/planner',
          '/reviews',
          '/seating',
          '/templates',
          '/timeline',
          '/vendor',
          '/weddings',
          '/forgot-password',
          '/password-reset',
        ],
      },
      {
        userAgent: 'GPTBot',
        disallow: '/',
      },
    ],
    sitemap: 'https://nikago.com/sitemap.xml',
  };
}
