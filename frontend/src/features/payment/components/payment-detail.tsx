'use client';

import { Button } from '@/components/ui/button';
import type { Payment } from '@/types';

interface PaymentDetailProps {
  payment: Payment;
  onPay?: () => void;
  onRefund?: () => void;
  isPaying?: boolean;
  isRefunding?: boolean;
}

function formatDate(dateStr: string | null): string {
  if (!dateStr) return '-';
  const d = new Date(dateStr);
  return d.toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' });
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

export function PaymentDetail({ payment, onPay, onRefund, isPaying, isRefunding }: PaymentDetailProps) {
  return (
    <div className="rounded-lg border bg-card p-6">
      <div className="flex items-center justify-between">
        <div>
          <h3 className="text-lg font-bold text-card-foreground">
            {payment.invoice_number}
          </h3>
          <span
            className={`mt-1 inline-block rounded-full px-2.5 py-0.5 text-xs font-medium ${statusColors[payment.status] ?? 'bg-gray-100 text-gray-800'}`}
          >
            {statusLabels[payment.status] ?? payment.status}
          </span>
        </div>
      </div>

      <div className="mt-4 space-y-3">
        {payment.items.map((item) => (
          <div key={item.id} className="flex items-center justify-between border-b pb-2 text-sm">
            <div>
              <p className="font-medium text-card-foreground">{item.name}</p>
              <p className="text-xs text-muted-foreground">
                {item.quantity}x {formatCurrency(item.amount)}
              </p>
            </div>
            <p className="font-medium text-card-foreground">
              {formatCurrency(item.amount * item.quantity)}
            </p>
          </div>
        ))}

        <div className="flex items-center justify-between border-t pt-2 text-sm">
          <p className="font-semibold text-card-foreground">Total</p>
          <p className="font-bold text-card-foreground">{formatCurrency(payment.amount)}</p>
        </div>
      </div>

      <div className="mt-4 grid grid-cols-2 gap-4 border-t pt-4 text-sm">
        <div>
          <p className="text-muted-foreground">Metode Pembayaran</p>
          <p className="font-medium text-card-foreground">{payment.payment_method?.name ?? '-'}</p>
        </div>
        <div>
          <p className="text-muted-foreground">Dibayar Pada</p>
          <p className="font-medium text-card-foreground">
            {formatDate(payment.paid_at)}
          </p>
        </div>
      </div>

      {payment.notes && (
        <div className="mt-4 border-t pt-4 text-sm">
          <p className="text-muted-foreground">Catatan</p>
          <p className="mt-1 text-card-foreground">{payment.notes}</p>
        </div>
      )}

      {payment.status === 'pending' && onPay && (
        <div className="mt-6">
          <Button className="w-full" onClick={onPay} disabled={isPaying}>
            {isPaying ? 'Memproses...' : 'Bayar Sekarang'}
          </Button>
        </div>
      )}

      {payment.status === 'paid' && onRefund && (
        <div className="mt-6">
          <Button variant="destructive" className="w-full" onClick={onRefund} disabled={isRefunding}>
            {isRefunding ? 'Memproses...' : 'Refund'}
          </Button>
        </div>
      )}
    </div>
  );
}
