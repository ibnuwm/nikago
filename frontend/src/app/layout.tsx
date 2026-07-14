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

export const metadata: Metadata = {
  title: {
    default: 'Nikago - Wedding Super App',
    template: '%s | Nikago',
  },
  description:
    'Plan your perfect wedding with Nikago. Manage guests, track budgets, find vendors, and create the wedding of your dreams with our all-in-one wedding planning platform.',
  keywords: ['wedding', 'planning', 'guest management', 'budget tracker', 'vendor directory', 'RSVP', 'wedding website'],
  authors: [{ name: 'Nikago' }],
  creator: 'Nikago',
  openGraph: {
    type: 'website',
    locale: 'en_US',
    url: 'https://nikago.com',
    siteName: 'Nikago',
    title: 'Nikago - Wedding Super App',
    description:
      'Plan your perfect wedding with Nikago. Manage guests, track budgets, find vendors, and create the wedding of your dreams.',
  },
  twitter: {
    card: 'summary_large_image',
    title: 'Nikago - Wedding Super App',
    description:
      'Plan your perfect wedding with Nikago. Manage guests, track budgets, find vendors, and create the wedding of your dreams.',
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
      <body className="min-h-full flex flex-col">
        <Providers>{children}</Providers>
      </body>
    </html>
  );
}
