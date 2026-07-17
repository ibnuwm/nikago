import type { Metadata } from 'next';
import { AuthLayout } from '@/features/auth/components/auth-layout';
import { LoginForm } from '@/features/auth/components/login-form';

export const metadata: Metadata = {
  title: 'Sign In',
  description: 'Sign in to your Nikago account to manage your wedding planning.',
  robots: { index: false, follow: false },
};

export default function LoginPage() {
  return (
    <AuthLayout
      title="Sign in to Nikago"
      description="Welcome back! Sign in to manage your wedding."
    >
      <LoginForm />
    </AuthLayout>
  );
}
