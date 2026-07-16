'use client';

import { useState } from 'react';
import type { ReviewFormData } from '@/types';

interface ReviewFormProps {
  onSubmit: (data: ReviewFormData) => void;
  onCancel?: () => void;
  initialData?: Partial<ReviewFormData>;
  isSubmitting?: boolean;
  bookingUuid?: string;
}

export function ReviewForm({
  onSubmit,
  onCancel,
  initialData,
  isSubmitting,
  bookingUuid,
}: ReviewFormProps) {
  const [rating, setRating] = useState(initialData?.rating ?? 0);
  const [review, setReview] = useState(initialData?.review ?? '');
  const [hoveredStar, setHoveredStar] = useState(0);

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (rating === 0) return;
    onSubmit({
      booking_uuid: bookingUuid ?? initialData?.booking_uuid ?? '',
      rating,
      review: review || null,
    });
  };

  return (
    <form onSubmit={handleSubmit} className="space-y-4">
      <div>
        <label className="text-xs font-medium text-foreground">Rating</label>
        <div className="mt-1 flex items-center gap-1">
          {[1, 2, 3, 4, 5].map((star) => (
            <button
              key={star}
              type="button"
              onClick={() => setRating(star)}
              onMouseEnter={() => setHoveredStar(star)}
              onMouseLeave={() => setHoveredStar(0)}
              className="transition-colors"
            >
              <svg
                className={`h-6 w-6 ${
                  star <= (hoveredStar || rating)
                    ? 'text-amber-400'
                    : 'text-muted-foreground/30'
                }`}
                fill="currentColor"
                viewBox="0 0 20 20"
              >
                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
              </svg>
            </button>
          ))}
          {rating > 0 && (
            <span className="ml-2 text-xs text-muted-foreground">
              {['Poor', 'Fair', 'Good', 'Very Good', 'Excellent'][rating - 1]}
            </span>
          )}
        </div>
      </div>

      <div>
        <label className="text-xs font-medium text-foreground">Review</label>
        <textarea
          value={review}
          onChange={(e) => setReview(e.target.value)}
          className="mt-1 w-full rounded border border-border bg-background px-3 py-2 text-xs text-foreground"
          rows={4}
          placeholder="Share your experience with this vendor..."
        />
      </div>

      <div className="flex gap-2">
        <button
          type="submit"
          disabled={rating === 0 || isSubmitting}
          className="inline-flex h-7 items-center justify-center rounded bg-primary px-3 text-xs text-primary-foreground hover:bg-primary/80 disabled:opacity-50"
        >
          {isSubmitting ? 'Submitting...' : 'Submit Review'}
        </button>
        {onCancel && (
          <button
            type="button"
            onClick={onCancel}
            className="inline-flex h-7 items-center justify-center rounded border border-border bg-background px-3 text-xs text-foreground hover:bg-muted"
          >
            Cancel
          </button>
        )}
      </div>
    </form>
  );
}
