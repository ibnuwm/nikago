'use client';

import { useParams } from 'next/navigation';
import { useVendorProfile } from '@/features/vendor/hooks/use-vendor-profile';
import { VendorProfile } from '@/features/vendor/components/vendor-profile';
import { useRouter } from 'next/navigation';

export default function VendorProfilePage() {
  const params = useParams();
  const uuid = typeof params.uuid === 'string' ? params.uuid : null;
  const router = useRouter();
  const { data: vendor, isLoading } = useVendorProfile(uuid);

  if (isLoading) {
    return (
      <div className="flex min-h-screen items-center justify-center bg-background">
        <p className="text-sm text-muted-foreground">Loading...</p>
      </div>
    );
  }

  if (!vendor) {
    return (
      <div className="flex min-h-screen items-center justify-center bg-background">
        <p className="text-sm text-muted-foreground">Vendor not found</p>
      </div>
    );
  }

  return <VendorProfile vendor={vendor} onBack={() => router.push('/vendor')} />;
}
