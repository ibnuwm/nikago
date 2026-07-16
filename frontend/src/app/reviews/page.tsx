'use client';

import { useState, useEffect } from 'react';
import { useRouter } from 'next/navigation';
import { useAuthStore } from '@/stores/auth-store';
import { useUser } from '@/hooks/use-auth';
import {
  useReviews,
  useCreateReview,
  useDeleteReview,
  useReportReview,
} from '@/features/review/hooks/use-reviews';
import { ReviewList } from '@/features/review/components/review-list';
import { ReviewForm } from '@/features/review/components/review-form';
import type { ReviewFormData } from '@/types';

type ViewState =
  | { type: 'list' }
  | { type: 'create' };

export default function ReviewsPage() {
  const { data: user, isLoading: isUserLoading } = useUser();
  const token = useAuthStore((s) => s.token);
  const router = useRouter();
  const [view, setView] = useState<ViewState>({ type: 'list' });

  const { data: reviewsData, isLoading: isReviewsLoading } = useReviews();
  const createReview = useCreateReview();
  const deleteReview = useDeleteReview();
  const reportReview = useReportReview();

  const reviews = reviewsData?.data ?? [];

  useEffect(() => {
    if (!token && !isUserLoading) {
      router.push('/login');
    }
  }, [token, isUserLoading, router]);

  const handleCreate = (data: ReviewFormData) => {
    createReview.mutate(data, {
      onSuccess: () => setView({ type: 'list' }),
    });
  };

  const handleDelete = (uuid: string) => {
    if (confirm('Are you sure you want to delete this review?')) {
      deleteReview.mutate(uuid);
    }
  };

  const handleReport = (uuid: string, reason: string) => {
    reportReview.mutate({ uuid, reason });
  };

  if (isUserLoading) {
    return (
      <div className="flex min-h-screen items-center justify-center bg-background">
        <p className="text-sm text-muted-foreground">Loading...</p>
      </div>
    );
  }

  if (!user) {
    return null;
  }

  return (
    <div className="min-h-screen bg-background">
      <header className="border-b bg-card">
        <div className="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
          <h1 className="text-xl font-bold text-card-foreground">My Reviews</h1>
          <div className="flex items-center gap-4">
            <span className="text-sm text-muted-foreground">{user.name}</span>
            <a
              href="/dashboard"
              className="inline-flex h-7 shrink-0 items-center justify-center gap-1 rounded-[min(var(--radius-md),12px)] border border-border bg-background px-2.5 text-[0.8rem] font-medium whitespace-nowrap text-foreground transition-all outline-none select-none hover:bg-muted hover:text-foreground"
            >
              Dashboard
            </a>
          </div>
        </div>
      </header>

      <main className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div className="border-b border-border">
          <nav className="-mb-px flex gap-4">
            {[
              { key: 'list', label: 'My Reviews' },
              { key: 'create', label: 'New Review' },
            ].map((tab) => (
              <button
                key={tab.key}
                type="button"
                onClick={() => setView({ type: tab.key as ViewState['type'] })}
                className={`pb-3 text-sm font-medium transition-colors ${
                  view.type === tab.key
                    ? 'border-b-2 border-primary text-primary'
                    : 'text-muted-foreground hover:text-foreground'
                }`}
              >
                {tab.label}
              </button>
            ))}
          </nav>
        </div>

        <div className="mt-6">
          {view.type === 'list' && (
            <ReviewList
              reviews={reviews}
              canDelete
              onDelete={handleDelete}
              onReport={handleReport}
              isLoading={isReviewsLoading}
            />
          )}

          {view.type === 'create' && (
            <div className="mx-auto max-w-lg">
              <h2 className="text-lg font-bold text-foreground">New Review</h2>
              <p className="mt-1 text-sm text-muted-foreground">
                Share your experience with a vendor.
              </p>

              <div className="mt-6">
                <ReviewForm
                  onSubmit={handleCreate}
                  onCancel={() => setView({ type: 'list' })}
                  isSubmitting={createReview.isPending}
                />
              </div>
            </div>
          )}
        </div>
      </main>
    </div>
  );
}
