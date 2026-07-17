import type { Metadata } from 'next';
import { AuthLayout } from '@/features/auth/components/auth-layout';
import { ResetPasswordForm } from '@/features/auth/components/reset-password-form';

export const metadata: Metadata = {
  title: 'Reset Password',
  description: 'Set a new password for your Nikago account.',
  robots: { index: false, follow: false },
};

export default function ResetPasswordPage() {
  return (
    <AuthLayout
      title="Reset your password"
      description="Enter your new password below."
    >
      <ResetPasswordForm />
    </AuthLayout>
  );
}
