import Link from 'next/link';

interface AuthLayoutProps {
  children: React.ReactNode;
  title: string;
  description?: string;
}

export function AuthLayout({ children, title, description }: AuthLayoutProps) {
  return (
    <div className="flex min-h-screen">
      <div className="hidden w-1/2 bg-gradient-to-br from-pink-100 to-purple-100 lg:flex lg:flex-col lg:items-center lg:justify-center">
        <div className="max-w-md space-y-4 px-8 text-center">
          <Link href="/" className="text-3xl font-bold text-gray-900">
            Nikago
          </Link>
          <p className="text-lg text-gray-600">
            Your Wedding Super App. Plan, organize, and celebrate your perfect day.
          </p>
        </div>
      </div>
      <div className="flex w-full flex-col items-center justify-center px-6 py-12 lg:w-1/2">
        <div className="w-full max-w-sm space-y-6">
          <div className="space-y-2 text-center">
            <h1 className="text-2xl font-bold tracking-tight text-gray-900">
              {title}
            </h1>
            {description && (
              <p className="text-sm text-gray-600">{description}</p>
            )}
          </div>
          {children}
        </div>
      </div>
    </div>
  );
}
