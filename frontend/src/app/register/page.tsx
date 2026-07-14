import { AuthLayout } from '@/features/auth/components/auth-layout';
import { RegisterForm } from '@/features/auth/components/register-form';

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
