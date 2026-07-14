'use client';

import { useFaqs } from '@/features/cms/hooks/use-cms';

export function FaqSection() {
  const { data: faqs, isLoading } = useFaqs();

  return (
    <section id="faq" className="py-20 sm:py-32 bg-background">
      <div className="mx-auto max-w-7xl px-6 lg:px-8">
        <div className="mx-auto max-w-2xl text-center">
          <h2 className="text-3xl font-bold tracking-tight text-foreground sm:text-4xl">
            Frequently Asked Questions
          </h2>
          <p className="mt-6 text-lg leading-8 text-muted-foreground">
            Everything you need to know about Nikago.
          </p>
        </div>
        <div className="mx-auto mt-16 max-w-2xl">
          {isLoading ? (
            <div className="space-y-4">
              {[...Array(3)].map((_, i) => (
                <div
                  key={i}
                  className="animate-pulse rounded-lg border bg-card p-6"
                >
                  <div className="h-4 w-3/4 rounded bg-muted" />
                  <div className="mt-3 h-3 w-full rounded bg-muted" />
                  <div className="mt-1 h-3 w-2/3 rounded bg-muted" />
                </div>
              ))}
            </div>
          ) : faqs && faqs.length > 0 ? (
            <div className="space-y-4">
              {faqs.map((faq) => (
                <details
                  key={faq.id}
                  className="group rounded-lg border bg-card"
                >
                  <summary className="flex cursor-pointer items-center justify-between p-6 text-sm font-semibold text-card-foreground">
                    {faq.question}
                    <svg
                      className="h-5 w-5 shrink-0 text-muted-foreground transition-transform group-open:rotate-180"
                      fill="none"
                      viewBox="0 0 24 24"
                      strokeWidth="2"
                      stroke="currentColor"
                    >
                      <path
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        d="M19.5 8.25l-7.5 7.5-7.5-7.5"
                      />
                    </svg>
                  </summary>
                  <div className="px-6 pb-6 text-sm text-muted-foreground">
                    {faq.answer}
                  </div>
                </details>
              ))}
            </div>
          ) : (
            <div className="rounded-lg border bg-card p-6 text-center">
              <p className="text-sm text-muted-foreground">
                No FAQs available yet. Check back soon!
              </p>
            </div>
          )}
        </div>
      </div>
    </section>
  );
}
