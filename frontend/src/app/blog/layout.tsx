import type { Metadata } from 'next';

export const metadata: Metadata = {
  title: 'Blog',
  description:
    'Wedding tips, real wedding stories, vendor spotlights, and planning guides. Get inspired and plan your perfect wedding with Nikago.',
  alternates: {
    canonical: 'https://nikago.com/blog',
  },
  openGraph: {
    title: 'Blog | Nikago',
    description:
      'Wedding tips, real wedding stories, vendor spotlights, and planning guides.',
    url: 'https://nikago.com/blog',
    siteName: 'Nikago',
    type: 'website',
  },
};

const breadcrumbJsonLd = {
  '@context': 'https://schema.org',
  '@type': 'BreadcrumbList',
  itemListElement: [
    {
      '@type': 'ListItem',
      position: 1,
      name: 'Home',
      item: 'https://nikago.com',
    },
    {
      '@type': 'ListItem',
      position: 2,
      name: 'Blog',
      item: 'https://nikago.com/blog',
    },
  ],
};

export default function BlogLayout({
  children,
}: {
  children: React.ReactNode;
}) {
  return (
    <>
      <script
        type="application/ld+json"
        dangerouslySetInnerHTML={{ __html: JSON.stringify(breadcrumbJsonLd) }}
      />
      {children}
    </>
  );
}
