'use client';

import { useState } from 'react';
import type { Review } from '@/types';
import { RatingSummary } from './rating-summary';

interface ReviewListProps {
  reviews: Review[];
  vendorRating?: number;
  vendorTotalReview?: number;
  canDelete?: boolean;
  canReply?: boolean;
  onDelete?: (uuid: string) => void;
  onReply?: (uuid: string, reply: string) => void;
  onReport?: (uuid: string, reason: string) => void;
  isLoading?: boolean;
}

export function ReviewList({
  reviews,
  vendorRating,
  vendorTotalReview,
  canDelete,
  canReply,
  onDelete,
  onReply,
  onReport,
  isLoading,
}: ReviewListProps) {
  const [replyInput, setReplyInput] = useState<Record<string, string>>({});
  const [showReplyForm, setShowReplyForm] = useState<Record<string, boolean>>({});
  const [reportReason, setReportReason] = useState('');
  const [showReportForm, setShowReportForm] = useState<string | null>(null);

  const handleReply = (uuid: string) => {
    const reply = replyInput[uuid]?.trim();
    if (reply && onReply) {
      onReply(uuid, reply);
      setReplyInput((prev) => ({ ...prev, [uuid]: '' }));
      setShowReplyForm((prev) => ({ ...prev, [uuid]: false }));
    }
  };

  const handleReport = (uuid: string) => {
    if (reportReason.trim() && onReport) {
      onReport(uuid, reportReason.trim());
      setReportReason('');
      setShowReportForm(null);
    }
  };

  if (isLoading) {
    return (
      <div className="flex justify-center py-8">
        <p className="text-sm text-muted-foreground">Loading reviews...</p>
      </div>
    );
  }

  if (reviews.length === 0) {
    return (
      <div className="rounded-lg border bg-card p-12 text-center shadow-sm">
        <p className="text-muted-foreground">No reviews yet.</p>
      </div>
    );
  }

  return (
    <div className="space-y-6">
      {(vendorRating !== undefined || vendorTotalReview !== undefined) && (
        <div className="rounded-lg border bg-card p-4 shadow-sm">
          <RatingSummary
            rating={vendorRating ?? 0}
            totalReview={vendorTotalReview ?? 0}
          />
        </div>
      )}

      <div className="space-y-4">
        {reviews.map((review) => (
          <div
            key={review.id}
            className="rounded-lg border bg-card p-4 shadow-sm"
          >
            <div className="flex items-start justify-between">
              <div className="flex items-center gap-2">
                <div className="flex h-8 w-8 items-center justify-center rounded-full bg-primary/10 text-xs font-medium text-primary">
                  {review.user_name?.charAt(0) ?? 'U'}
                </div>
                <div>
                  <p className="text-xs font-medium text-card-foreground">
                    {review.user_name ?? 'Anonymous'}
                  </p>
                  <div className="flex items-center gap-1">
                    {[1, 2, 3, 4, 5].map((star) => (
                      <svg
                        key={star}
                        className={`h-3 w-3 ${
                          star <= review.rating
                            ? 'text-amber-400'
                            : 'text-muted-foreground/30'
                        }`}
                        fill="currentColor"
                        viewBox="0 0 20 20"
                      >
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                    ))}
                  </div>
                </div>
              </div>

              <div className="flex items-center gap-1">
                {canDelete && onDelete && (
                  <button
                    type="button"
                    onClick={() => onDelete(review.id)}
                    className="text-xs text-red-500 hover:text-red-600"
                  >
                    Delete
                  </button>
                )}
                <button
                  type="button"
                  onClick={() => setShowReportForm(showReportForm === review.id ? null : review.id)}
                  className="text-xs text-muted-foreground hover:text-foreground"
                >
                  Report
                </button>
              </div>
            </div>

            {review.review && (
              <p className="mt-2 text-sm text-card-foreground">{review.review}</p>
            )}

            {review.images && review.images.length > 0 && (
              <div className="mt-2 flex gap-2 overflow-x-auto">
                {review.images.map((img) => (
                  <img
                    key={img.id}
                    src={img.image_url}
                    alt="Review photo"
                    className="h-16 w-16 rounded object-cover"
                  />
                ))}
              </div>
            )}

            {review.reply && (
              <div className="mt-3 rounded-lg bg-muted p-3">
                <p className="text-xs font-medium text-foreground">Vendor Response</p>
                <p className="mt-1 text-xs text-muted-foreground">{review.reply}</p>
                {review.replied_at && (
                  <p className="mt-1 text-[10px] text-muted-foreground">
                    {new Date(review.replied_at).toLocaleDateString('id-ID')}
                  </p>
                )}
              </div>
            )}

            {canReply && !review.reply && (
              <div className="mt-3">
                {showReplyForm[review.id] ? (
                  <div className="space-y-2">
                    <textarea
                      value={replyInput[review.id] ?? ''}
                      onChange={(e) =>
                        setReplyInput((prev) => ({ ...prev, [review.id]: e.target.value }))
                      }
                      className="w-full rounded border border-border bg-background px-2 py-1 text-xs text-foreground"
                      rows={2}
                      placeholder="Write your reply..."
                    />
                    <div className="flex gap-2">
                      <button
                        type="button"
                        onClick={() => handleReply(review.id)}
                        disabled={!replyInput[review.id]?.trim()}
                        className="inline-flex h-6 items-center justify-center rounded bg-primary px-2 text-[10px] text-primary-foreground hover:bg-primary/80 disabled:opacity-50"
                      >
                        Send
                      </button>
                      <button
                        type="button"
                        onClick={() =>
                          setShowReplyForm((prev) => ({ ...prev, [review.id]: false }))
                        }
                        className="inline-flex h-6 items-center justify-center rounded border border-border bg-background px-2 text-[10px] text-foreground hover:bg-muted"
                      >
                        Cancel
                      </button>
                    </div>
                  </div>
                ) : (
                  <button
                    type="button"
                    onClick={() =>
                      setShowReplyForm((prev) => ({ ...prev, [review.id]: true }))
                    }
                    className="text-xs text-blue-600 hover:text-blue-700"
                  >
                    Reply
                  </button>
                )}
              </div>
            )}

            {showReportForm === review.id && (
              <div className="mt-3 rounded-lg border border-red-200 bg-red-50 p-3">
                <p className="text-xs font-medium text-red-700">Report this review</p>
                <textarea
                  value={reportReason}
                  onChange={(e) => setReportReason(e.target.value)}
                  className="mt-1 w-full rounded border border-red-300 bg-white px-2 py-1 text-xs text-foreground"
                  rows={2}
                  placeholder="Reason for reporting..."
                />
                <div className="mt-2 flex gap-2">
                  <button
                    type="button"
                    onClick={() => handleReport(review.id)}
                    disabled={!reportReason.trim()}
                    className="inline-flex h-6 items-center justify-center rounded bg-red-600 px-2 text-[10px] text-white hover:bg-red-700 disabled:opacity-50"
                  >
                    Submit Report
                  </button>
                  <button
                    type="button"
                    onClick={() => setShowReportForm(null)}
                    className="inline-flex h-6 items-center justify-center rounded border border-border bg-white px-2 text-[10px] text-foreground hover:bg-muted"
                  >
                    Cancel
                  </button>
                </div>
              </div>
            )}

            <p className="mt-2 text-[10px] text-muted-foreground">
              {new Date(review.created_at).toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
              })}
            </p>
          </div>
        ))}
      </div>
    </div>
  );
}
