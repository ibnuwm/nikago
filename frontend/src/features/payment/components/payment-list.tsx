'use client';

import Link from 'next/link';
import type { Payment } from '@/types';

interface PaymentListProps {
  payments: Payment[];
}

function formatDate(dateStr: string | null): string {
  if (!dateStr) return '-';
  const d = new Date(dateStr);
  return d.toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric' });
}

function formatCurrency(amount: number): string {
  return `Rp${amount.toLocaleString('id-ID')}`;
}

const statusLabels: Record<string, string> = {
  pending: 'Menunggu',
  paid: 'Lunas',
  failed: 'Gagal',
  refunded: 'Dikembalikan',
};

const statusColors: Record<string, string> = {
  pending: 'bg-yellow-100 text-yellow-800',
  paid: 'bg-green-100 text-green-800',
  failed: 'bg-red-100 text-red-800',
  refunded: 'bg-blue-100 text-blue-800',
};

export function PaymentList({ payments }: PaymentListProps) {
  if (payments.length === 0) {
    return (
      <div className="py-12 text-center">
        <p className="text-sm text-muted-foreground">Belum ada pembayaran.</p>
      </div>
    );
  }

  return (
    <div className="space-y-3">
      {payments.map((payment) => (
        <Link
          key={payment.id}
          href={`/payments/${payment.id}`}
          className="block rounded-lg border bg-card p-4 transition-colors hover:bg-muted/50"
        >
          <div className="flex items-center justify-between">
            <div>
              <p className="text-sm font-medium text-card-foreground">
                {payment.invoice_number}
              </p>
              <p className="text-xs text-muted-foreground">
                {formatDate(payment.created_at)}
              </p>
            </div>
            <div className="text-right">
              <p className="text-sm font-semibold text-card-foreground">
                {formatCurrency(payment.amount)}
              </p>
              <span
                className={`inline-block rounded-full px-2 py-0.5 text-xs font-medium ${statusColors[payment.status] ?? 'bg-gray-100 text-gray-800'}`}
              >
                {statusLabels[payment.status] ?? payment.status}
              </span>
            </div>
          </div>
        </Link>
      ))}
    </div>
  );
}
