'use client';

import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { z } from 'zod';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import type { Wedding, WeddingFormData } from '@/types';

const weddingSchema = z.object({
  title: z.string().min(1, 'Title is required').max(255, 'Title must be less than 255 characters'),
  theme: z.string().max(100, 'Theme must be less than 100 characters').nullable().optional(),
  cover_image: z.string().max(500, 'Cover image URL must be less than 500 characters').nullable().optional(),
});

interface WeddingFormProps {
  wedding?: Wedding;
  onSubmit: (data: WeddingFormData) => void;
  isLoading?: boolean;
}

export function WeddingForm({ wedding, onSubmit, isLoading }: WeddingFormProps) {
  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm<WeddingFormData>({
    resolver: zodResolver(weddingSchema),
    defaultValues: {
      title: wedding?.title || '',
      theme: wedding?.theme || '',
      cover_image: wedding?.cover_image || '',
    },
  });

  return (
    <form onSubmit={handleSubmit(onSubmit)} className="space-y-6">
      <div>
        <label htmlFor="title" className="block text-sm font-medium text-foreground">
          Title *
        </label>
        <Input
          id="title"
          {...register('title')}
          placeholder="Enter wedding title"
          className="mt-1"
        />
        {errors.title && (
          <p className="mt-1 text-sm text-destructive">{errors.title.message}</p>
        )}
      </div>

      <div>
        <label htmlFor="theme" className="block text-sm font-medium text-foreground">
          Theme
        </label>
        <Input
          id="theme"
          {...register('theme')}
          placeholder="Enter wedding theme"
          className="mt-1"
        />
        {errors.theme && (
          <p className="mt-1 text-sm text-destructive">{errors.theme.message}</p>
        )}
      </div>

      <div>
        <label htmlFor="cover_image" className="block text-sm font-medium text-foreground">
          Cover Image URL
        </label>
        <Input
          id="cover_image"
          {...register('cover_image')}
          placeholder="Enter cover image URL"
          className="mt-1"
        />
        {errors.cover_image && (
          <p className="mt-1 text-sm text-destructive">{errors.cover_image.message}</p>
        )}
      </div>

      <Button type="submit" disabled={isLoading}>
        {isLoading ? 'Saving...' : wedding ? 'Update Wedding' : 'Create Wedding'}
      </Button>
    </form>
  );
}
