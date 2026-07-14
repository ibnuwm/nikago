'use client';

import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { z } from 'zod';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import type { Invitation, InvitationFormData } from '@/types';

const invitationSchema = z.object({
  wedding_id: z.string().min(1, 'Wedding is required'),
  title: z.string().min(1, 'Title is required').max(255, 'Title must be less than 255 characters'),
  slug: z.string().max(255, 'Slug must be less than 255 characters').optional(),
  cover_image: z.string().max(500, 'Cover image URL must be less than 500 characters').nullable().optional(),
  description: z.string().nullable().optional(),
});

interface InvitationFormProps {
  invitation?: Invitation;
  onSubmit: (data: InvitationFormData) => void;
  isLoading?: boolean;
}

export function InvitationForm({ invitation, onSubmit, isLoading }: InvitationFormProps) {
  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm<InvitationFormData>({
    resolver: zodResolver(invitationSchema),
    defaultValues: {
      wedding_id: invitation?.wedding_id || '',
      title: invitation?.title || '',
      slug: invitation?.slug || '',
      cover_image: invitation?.cover_image || '',
      description: invitation?.description || '',
    },
  });

  return (
    <form onSubmit={handleSubmit(onSubmit)} className="space-y-6">
      <div>
        <label htmlFor="wedding_id" className="block text-sm font-medium text-foreground">
          Wedding ID *
        </label>
        <Input
          id="wedding_id"
          {...register('wedding_id')}
          placeholder="Enter wedding UUID"
          className="mt-1"
        />
        {errors.wedding_id && (
          <p className="mt-1 text-sm text-destructive">{errors.wedding_id.message}</p>
        )}
      </div>

      <div>
        <label htmlFor="title" className="block text-sm font-medium text-foreground">
          Title *
        </label>
        <Input
          id="title"
          {...register('title')}
          placeholder="Enter invitation title"
          className="mt-1"
        />
        {errors.title && (
          <p className="mt-1 text-sm text-destructive">{errors.title.message}</p>
        )}
      </div>

      <div>
        <label htmlFor="slug" className="block text-sm font-medium text-foreground">
          Slug
        </label>
        <Input
          id="slug"
          {...register('slug')}
          placeholder="Enter URL slug (auto-generated if empty)"
          className="mt-1"
        />
        {errors.slug && (
          <p className="mt-1 text-sm text-destructive">{errors.slug.message}</p>
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

      <div>
        <label htmlFor="description" className="block text-sm font-medium text-foreground">
          Description
        </label>
        <textarea
          id="description"
          {...register('description')}
          placeholder="Enter invitation description"
          className="mt-1 block w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
          rows={3}
        />
        {errors.description && (
          <p className="mt-1 text-sm text-destructive">{errors.description.message}</p>
        )}
      </div>

      <Button type="submit" disabled={isLoading}>
        {isLoading ? 'Saving...' : invitation ? 'Update Invitation' : 'Create Invitation'}
      </Button>
    </form>
  );
}
