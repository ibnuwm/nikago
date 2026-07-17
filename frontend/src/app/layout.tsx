import type { Metadata } from "next";
import { Geist, Geist_Mono } from "next/font/google";
import { Providers } from "@/providers/query-provider";
import "./globals.css";

const geistSans = Geist({
  variable: "--font-geist-sans",
  subsets: ["latin"],
});

const geistMono = Geist_Mono({
  variable: "--font-geist-mono",
  subsets: ["latin"],
});

const BASE_URL = 'https://nikago.com';

export const metadata: Metadata = {
  metadataBase: new URL(BASE_URL),
  title: {
    default: 'Nikago - Wedding Super App',
    template: '%s | Nikago',
  },
  description:
    'Plan your perfect wedding with Nikago. Manage guests, track budgets, find vendors, and create the wedding of your dreams with our all-in-one wedding planning platform.',
  keywords: ['wedding', 'planning', 'guest management', 'budget tracker', 'vendor directory', 'RSVP', 'wedding website'],
  authors: [{ name: 'Nikago' }],
  creator: 'Nikago',
  publisher: 'Nikago',
  openGraph: {
    type: 'website',
    locale: 'en_US',
    url: BASE_URL,
    siteName: 'Nikago',
    title: 'Nikago - Wedding Super App',
    description:
      'Plan your perfect wedding with Nikago. Manage guests, track budgets, find vendors, and create the wedding of your dreams.',
    images: [
      {
        url: '/og-image.png',
        width: 1200,
        height: 630,
        alt: 'Nikago - Wedding Super App',
      },
    ],
  },
  twitter: {
    card: 'summary_large_image',
    title: 'Nikago - Wedding Super App',
    description:
      'Plan your perfect wedding with Nikago. Manage guests, track budgets, find vendors, and create the wedding of your dreams.',
    images: ['/og-image.png'],
  },
  robots: {
    index: true,
    follow: true,
    googleBot: {
      index: true,
      follow: true,
      'max-video-preview': -1,
      'max-image-preview': 'large',
      'max-snippet': -1,
    },
  },
  alternates: {
    canonical: BASE_URL,
  },
};

const jsonLd = {
  '@context': 'https://schema.org',
  '@type': 'Organization',
  name: 'Nikago',
  url: BASE_URL,
  logo: `${BASE_URL}/logo.png`,
  description:
    'Nikago is your all-in-one wedding planning platform. Organize guests, track budgets, find vendors, and create the wedding of your dreams.',
  sameAs: [
    'https://facebook.com/nikago',
    'https://instagram.com/nikago',
    'https://twitter.com/nikago',
  ],
};

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode;
}>) {
  return (
    <html
      lang="en"
      className={`${geistSans.variable} ${geistMono.variable} h-full antialiased`}
    >
      <head>
        <script
          type="application/ld+json"
          dangerouslySetInnerHTML={{ __html: JSON.stringify(jsonLd) }}
        />
      </head>
      <body className="min-h-full flex flex-col">
        <Providers>{children}</Providers>
      </body>
    </html>
  );
}
