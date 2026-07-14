import { AuthLayout } from '@/features/auth/components/auth-layout';
import { ForgotPasswordForm } from '@/features/auth/components/forgot-password-form';

export default function ForgotPasswordPage() {
  return (
    <AuthLayout
      title="Forgot your password?"
      description="Enter your email and we'll send you a reset link."
    >
      <ForgotPasswordForm />
    </AuthLayout>
  );
}
