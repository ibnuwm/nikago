import { AuthLayout } from '@/features/auth/components/auth-layout';
import { ResetPasswordForm } from '@/features/auth/components/reset-password-form';

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
