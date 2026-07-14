'use client';

import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { z } from 'zod';
import { useForgotPassword } from '@/hooks/use-auth';
import { Button } from '@/components/ui/button';
import Link from 'next/link';
import { useState } from 'react';

const forgotPasswordSchema = z.object({
  email: z.string().email('Please enter a valid email address'),
});

type ForgotPasswordFormData = z.infer<typeof forgotPasswordSchema>;

export function ForgotPasswordForm() {
  const forgotPassword = useForgotPassword();
  const [submitted, setSubmitted] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm<ForgotPasswordFormData>({
    resolver: zodResolver(forgotPasswordSchema),
  });

  const onSubmit = (data: ForgotPasswordFormData) => {
    setError(null);
    forgotPassword.mutate(data, {
      onSuccess: () => {
        setSubmitted(true);
      },
      onError: (err: unknown) => {
        const apiErr = err as { response?: { data?: { error?: { message?: string } } } };
        setError(apiErr.response?.data?.error?.message || 'Failed to send reset link. Please try again.');
      },
    });
  };

  if (submitted) {
    return (
      <div className="space-y-4 text-center">
        <div className="rounded-md bg-green-50 p-3 text-sm text-green-600">
          If an account exists with that email, we&apos;ve sent a password reset link.
        </div>
        <Link
          href="/login"
          className="inline-block text-sm font-medium text-pink-600 hover:text-pink-500"
        >
          Back to sign in
        </Link>
      </div>
    );
  }

  return (
    <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
      {error && (
        <div className="rounded-md bg-red-50 p-3 text-sm text-red-600">
          {error}
        </div>
      )}

      <div className="space-y-2">
        <label htmlFor="email" className="text-sm font-medium text-gray-700">
          Email address
        </label>
        <input
          id="email"
          type="email"
          autoComplete="email"
          {...register('email')}
          className="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-pink-500"
          placeholder="you@example.com"
        />
        {errors.email && (
          <p className="text-xs text-red-500">{errors.email.message}</p>
        )}
      </div>

      <Button
        type="submit"
        className="w-full"
        size="lg"
        disabled={forgotPassword.isPending}
      >
        {forgotPassword.isPending ? 'Sending...' : 'Send reset link'}
      </Button>

      <p className="text-center text-sm text-gray-600">
        Remember your password?{' '}
        <Link href="/login" className="font-medium text-pink-600 hover:text-pink-500">
          Sign in
        </Link>
      </p>
    </form>
  );
}
