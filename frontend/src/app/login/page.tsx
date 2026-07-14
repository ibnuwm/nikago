import { AuthLayout } from '@/features/auth/components/auth-layout';
import { LoginForm } from '@/features/auth/components/login-form';

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
