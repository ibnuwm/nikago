import type { Metadata } from 'next';
import { AuthLayout } from '@/features/auth/components/auth-layout';
import { RegisterForm } from '@/features/auth/components/register-form';

export const metadata: Metadata = {
  title: 'Create Account',
  description: 'Create your free Nikago account and start planning your dream wedding today.',
  robots: { index: false, follow: false },
};

export default function RegisterPage() {
  return (
    <AuthLayout
      title="Create your account"
      description="Join Nikago and start planning your perfect wedding."
    >
      <RegisterForm />
    </AuthLayout>
  );
}
