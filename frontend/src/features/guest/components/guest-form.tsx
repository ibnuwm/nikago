'use client';

import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { z } from 'zod';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import type { Guest, GuestFormData } from '@/types';

const guestSchema = z.object({
  wedding_id: z.number({ required_error: 'Wedding is required' }),
  name: z.string().min(1, 'Name is required').max(255, 'Name must be less than 255 characters'),
  phone: z.string().max(25, 'Phone must be less than 25 characters').nullable().optional(),
  email: z.string().email('Invalid email').max(255).nullable().optional().or(z.literal('')),
  address: z.string().nullable().optional(),
  pax: z.number().min(1, 'Pax must be at least 1').max(32767).optional(),
  status: z.string().optional(),
});

interface GuestFormProps {
  guest?: Guest;
  weddingId: number;
  onSubmit: (data: GuestFormData) => void;
  isLoading?: boolean;
}

export function GuestForm({ guest, weddingId, onSubmit, isLoading }: GuestFormProps) {
  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm<GuestFormData>({
    resolver: zodResolver(guestSchema),
    defaultValues: {
      wedding_id: weddingId,
      name: guest?.name || '',
      phone: guest?.phone || '',
      email: guest?.email || '',
      address: guest?.address || '',
      pax: guest?.pax || 1,
      status: guest?.status || 'active',
    },
  });

  return (
    <form onSubmit={handleSubmit(onSubmit)} className="space-y-6">
      <div>
        <label htmlFor="name" className="block text-sm font-medium text-foreground">
          Name *
        </label>
        <Input
          id="name"
          {...register('name')}
          placeholder="Enter guest name"
          className="mt-1"
        />
        {errors.name && (
          <p className="mt-1 text-sm text-destructive">{errors.name.message}</p>
        )}
      </div>

      <div>
        <label htmlFor="phone" className="block text-sm font-medium text-foreground">
          Phone
        </label>
        <Input
          id="phone"
          {...register('phone')}
          placeholder="Enter phone number"
          className="mt-1"
        />
        {errors.phone && (
          <p className="mt-1 text-sm text-destructive">{errors.phone.message}</p>
        )}
      </div>

      <div>
        <label htmlFor="email" className="block text-sm font-medium text-foreground">
          Email
        </label>
        <Input
          id="email"
          type="email"
          {...register('email')}
          placeholder="Enter email address"
          className="mt-1"
        />
        {errors.email && (
          <p className="mt-1 text-sm text-destructive">{errors.email.message}</p>
        )}
      </div>

      <div>
        <label htmlFor="address" className="block text-sm font-medium text-foreground">
          Address
        </label>
        <Input
          id="address"
          {...register('address')}
          placeholder="Enter address"
          className="mt-1"
        />
        {errors.address && (
          <p className="mt-1 text-sm text-destructive">{errors.address.message}</p>
        )}
      </div>

      <div>
        <label htmlFor="pax" className="block text-sm font-medium text-foreground">
          Pax (Number of People)
        </label>
        <Input
          id="pax"
          type="number"
          min={1}
          {...register('pax', { valueAsNumber: true })}
          className="mt-1"
        />
        {errors.pax && (
          <p className="mt-1 text-sm text-destructive">{errors.pax.message}</p>
        )}
      </div>

      <Button type="submit" disabled={isLoading}>
        {isLoading ? 'Saving...' : guest ? 'Update Guest' : 'Add Guest'}
      </Button>
    </form>
  );
}
