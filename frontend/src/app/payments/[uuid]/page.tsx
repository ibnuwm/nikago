'use client';

import { useRouter, useParams } from 'next/navigation';
import { useEffect } from 'react';
import Link from 'next/link';
import { useAuthStore } from '@/stores/auth-store';
import { useUser } from '@/hooks/use-auth';
import { PaymentDetail } from '@/features/payment/components/payment-detail';
import { usePayment, usePayPayment, useRefundPayment } from '@/features/payment/hooks/use-payment';

export default function PaymentDetailPage() {
  const { uuid } = useParams<{ uuid: string }>();
  const token = useAuthStore((s) => s.token);
  const router = useRouter();
  const { data: user, isLoading: isUserLoading } = useUser();

  useEffect(() => {
    if (!token && !isUserLoading) {
      router.push('/login');
    }
  }, [token, isUserLoading, router]);

  const { data: payment, isLoading } = usePayment(uuid);
  const pay = usePayPayment();
  const refund = useRefundPayment();

  const handlePay = () => {
    pay.mutate({ uuid });
  };

  const handleRefund = () => {
    if (confirm('Refund pembayaran ini?')) {
      refund.mutate(uuid);
    }
  };

  if (!token || !user) return null;

  return (
    <div className="min-h-screen bg-background">
      <header className="border-b bg-card">
        <div className="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
          <h1 className="text-xl font-bold text-card-foreground">Detail Pembayaran</h1>
          <div className="flex items-center gap-4">
            <span className="text-sm text-muted-foreground">{user.name}</span>
            <a
              href="/dashboard"
              className="inline-flex h-7 shrink-0 items-center justify-center gap-1 rounded-[min(var(--radius-md),12px)] border border-border bg-background px-2.5 text-[0.8rem] font-medium whitespace-nowrap text-foreground transition-all outline-none select-none hover:bg-muted hover:text-foreground"
            >
              Back to Dashboard
            </a>
          </div>
        </div>
      </header>

      <main className="mx-auto max-w-3xl px-4 py-8 sm:px-6 lg:px-8">
        <Link
          href="/payments"
          className="mb-6 inline-flex items-center gap-1 text-sm text-muted-foreground hover:text-foreground"
        >
          &larr; Kembali ke Pembayaran
        </Link>

        {isLoading ? (
          <div className="py-12 text-center">
            <p className="text-sm text-muted-foreground">Memuat detail...</p>
          </div>
        ) : !payment ? (
          <div className="py-12 text-center">
            <p className="text-sm text-muted-foreground">Pembayaran tidak ditemukan.</p>
          </div>
        ) : (
          <PaymentDetail
            payment={payment}
            onPay={handlePay}
            onRefund={handleRefund}
            isPaying={pay.isPending}
            isRefunding={refund.isPending}
          />
        )}
      </main>
    </div>
  );
}
