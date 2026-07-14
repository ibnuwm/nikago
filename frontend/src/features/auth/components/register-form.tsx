'use client';

import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { z } from 'zod';
import { useRegister } from '@/hooks/use-auth';
import { Button } from '@/components/ui/button';
import Link from 'next/link';
import { useRouter } from 'next/navigation';
import { useState } from 'react';

const registerSchema = z.object({
  name: z.string().min(1, 'Name is required').max(255, 'Name is too long'),
  email: z.string().email('Please enter a valid email address'),
  password: z
    .string()
    .min(8, 'Password must be at least 8 characters')
    .regex(/[A-Z]/, 'Password must contain at least one uppercase letter')
    .regex(/[a-z]/, 'Password must contain at least one lowercase letter')
    .regex(/[0-9]/, 'Password must contain at least one number'),
  password_confirmation: z.string().min(1, 'Please confirm your password'),
}).refine((data) => data.password === data.password_confirmation, {
  message: 'Passwords do not match',
  path: ['password_confirmation'],
});

type RegisterFormData = z.infer<typeof registerSchema>;

export function RegisterForm() {
  const register = useRegister();
  const router = useRouter();
  const [error, setError] = useState<string | null>(null);

  const {
    register: registerField,
    handleSubmit,
    formState: { errors },
  } = useForm<RegisterFormData>({
    resolver: zodResolver(registerSchema),
  });

  const onSubmit = (data: RegisterFormData) => {
    setError(null);
    register.mutate(data, {
      onSuccess: () => {
        router.push('/dashboard');
      },
      onError: (err: unknown) => {
        const apiErr = err as { response?: { data?: { error?: { message?: string } } } };
        setError(apiErr.response?.data?.error?.message || 'Registration failed. Please try again.');
      },
    });
  };

  return (
    <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
      {error && (
        <div className="rounded-md bg-red-50 p-3 text-sm text-red-600">
          {error}
        </div>
      )}

      <div className="space-y-2">
        <label htmlFor="name" className="text-sm font-medium text-gray-700">
          Name
        </label>
        <input
          id="name"
          type="text"
          autoComplete="name"
          {...registerField('name')}
          className="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-pink-500"
          placeholder="John Doe"
        />
        {errors.name && (
          <p className="text-xs text-red-500">{errors.name.message}</p>
        )}
      </div>

      <div className="space-y-2">
        <label htmlFor="email" className="text-sm font-medium text-gray-700">
          Email
        </label>
        <input
          id="email"
          type="email"
          autoComplete="email"
          {...registerField('email')}
          className="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-pink-500"
          placeholder="you@example.com"
        />
        {errors.email && (
          <p className="text-xs text-red-500">{errors.email.message}</p>
        )}
      </div>

      <div className="space-y-2">
        <label htmlFor="password" className="text-sm font-medium text-gray-700">
          Password
        </label>
        <input
          id="password"
          type="password"
          autoComplete="new-password"
          {...registerField('password')}
          className="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-pink-500"
          placeholder="••••••••"
        />
        {errors.password && (
          <p className="text-xs text-red-500">{errors.password.message}</p>
        )}
      </div>

      <div className="space-y-2">
        <label htmlFor="password_confirmation" className="text-sm font-medium text-gray-700">
          Confirm Password
        </label>
        <input
          id="password_confirmation"
          type="password"
          autoComplete="new-password"
          {...registerField('password_confirmation')}
          className="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-pink-500 focus:outline-none focus:ring-1 focus:ring-pink-500"
          placeholder="••••••••"
        />
        {errors.password_confirmation && (
          <p className="text-xs text-red-500">{errors.password_confirmation.message}</p>
        )}
      </div>

      <Button
        type="submit"
        className="w-full"
        size="lg"
        disabled={register.isPending}
      >
        {register.isPending ? 'Creating account...' : 'Create account'}
      </Button>

      <p className="text-center text-sm text-gray-600">
        Already have an account?{' '}
        <Link href="/login" className="font-medium text-pink-600 hover:text-pink-500">
          Sign in
        </Link>
      </p>
    </form>
  );
}
