'use client';

import { useState, useEffect } from 'react';
import { useRouter } from 'next/navigation';
import { useAuthStore } from '@/stores/auth-store';
import { useUser } from '@/hooks/use-auth';
import {
  useBookings,
  useCreateBooking,
  useConfirmBooking,
  useCancelBooking,
  useCompleteBooking,
  useBookingCalendar,
} from '@/features/booking/hooks/use-bookings';
import type { BookingFormData } from '@/types';

type ViewState =
  | { type: 'list' }
  | { type: 'create' }
  | { type: 'calendar' };

const STATUS_LABELS: Record<string, string> = {
  pending: 'Pending',
  confirmed: 'Confirmed',
  completed: 'Completed',
  cancelled: 'Cancelled',
};

const STATUS_COLORS: Record<string, string> = {
  pending: 'text-amber-600',
  confirmed: 'text-blue-600',
  completed: 'text-emerald-600',
  cancelled: 'text-red-500',
};

export default function BookingsPage() {
  const { data: user, isLoading: isUserLoading } = useUser();
  const token = useAuthStore((s) => s.token);
  const router = useRouter();
  const [view, setView] = useState<ViewState>({ type: 'list' });
  const [statusFilter, setStatusFilter] = useState<string>('');
  const [selectedBooking, setSelectedBooking] = useState<string | null>(null);

  const { data: bookingsData, isLoading: isBookingsLoading } = useBookings(
    statusFilter ? { status: statusFilter } : undefined
  );
  const createBooking = useCreateBooking();
  const confirmBooking = useConfirmBooking();
  const cancelBooking = useCancelBooking();
  const completeBooking = useCompleteBooking();

  const now = new Date();
  const { data: calendarData, isLoading: isCalendarLoading } = useBookingCalendar({
    year: now.getFullYear(),
    month: now.getMonth() + 1,
  });

  const bookings = bookingsData?.data ?? [];

  useEffect(() => {
    if (!token && !isUserLoading) {
      router.push('/login');
    }
  }, [token, isUserLoading, router]);

  const handleCreate = (data: BookingFormData) => {
    createBooking.mutate(data, {
      onSuccess: () => setView({ type: 'list' }),
    });
  };

  const [formData, setFormData] = useState<BookingFormData>({
    vendor_uuid: '',
    package_id: 0,
    event_date: '',
    wedding_id: 0,
    notes: '',
  });

  if (isUserLoading) {
    return (
      <div className="flex min-h-screen items-center justify-center bg-background">
        <p className="text-sm text-muted-foreground">Loading...</p>
      </div>
    );
  }

  if (!user) {
    return null;
  }

  const detailBooking = selectedBooking
    ? bookings.find((b) => b.id === selectedBooking)
    : null;

  return (
    <div className="min-h-screen bg-background">
      <header className="border-b bg-card">
        <div className="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
          <h1 className="text-xl font-bold text-card-foreground">Vendor Bookings</h1>
          <div className="flex items-center gap-4">
            <span className="text-sm text-muted-foreground">{user.name}</span>
            <a
              href="/dashboard"
              className="inline-flex h-7 shrink-0 items-center justify-center gap-1 rounded-[min(var(--radius-md),12px)] border border-border bg-background px-2.5 text-[0.8rem] font-medium whitespace-nowrap text-foreground transition-all outline-none select-none hover:bg-muted hover:text-foreground"
            >
              Dashboard
            </a>
          </div>
        </div>
      </header>

      <main className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div className="border-b border-border">
          <nav className="-mb-px flex gap-4">
            {[
              { key: 'list', label: 'My Bookings' },
              { key: 'create', label: 'New Booking' },
              { key: 'calendar', label: 'Calendar' },
            ].map((tab) => (
              <button
                key={tab.key}
                type="button"
                onClick={() => {
                  setView({ type: tab.key as ViewState['type'] });
                  setSelectedBooking(null);
                }}
                className={`pb-3 text-sm font-medium transition-colors ${
                  view.type === tab.key
                    ? 'border-b-2 border-primary text-primary'
                    : 'text-muted-foreground hover:text-foreground'
                }`}
              >
                {tab.label}
              </button>
            ))}
          </nav>
        </div>

        <div className="mt-6">
          {view.type === 'list' && (
            <>
              <div className="flex items-center justify-between">
                <h2 className="text-lg font-bold text-foreground">
                  {detailBooking ? 'Booking Detail' : 'All Bookings'}
                </h2>
                {!detailBooking && (
                  <select
                    value={statusFilter}
                    onChange={(e) => setStatusFilter(e.target.value)}
                    className="h-8 rounded border border-border bg-background px-2 text-xs text-foreground"
                  >
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                  </select>
                )}
              </div>

              {detailBooking ? (
                <div className="mt-4 space-y-4">
                  <button
                    type="button"
                    onClick={() => setSelectedBooking(null)}
                    className="text-xs text-muted-foreground hover:text-foreground"
                  >
                    ← Back to list
                  </button>

                  <div className="rounded-lg border bg-card p-5 shadow-sm">
                    <div className="flex items-center justify-between">
                      <div>
                        <h3 className="text-lg font-semibold text-card-foreground">
                          {detailBooking.vendor_name ?? 'Vendor'}
                        </h3>
                        <p className="text-sm text-muted-foreground">
                          {detailBooking.package_name}
                        </p>
                      </div>
                      <span className={`text-sm font-medium ${STATUS_COLORS[detailBooking.status] ?? ''}`}>
                        {STATUS_LABELS[detailBooking.status] ?? detailBooking.status}
                      </span>
                    </div>

                    <div className="mt-4 grid grid-cols-2 gap-4 text-sm">
                      <div>
                        <span className="text-muted-foreground">Booking Date:</span>
                        <p className="font-medium">{detailBooking.booking_date}</p>
                      </div>
                      <div>
                        <span className="text-muted-foreground">Event Date:</span>
                        <p className="font-medium">{detailBooking.event_date}</p>
                      </div>
                      <div>
                        <span className="text-muted-foreground">Subtotal:</span>
                        <p className="font-medium">
                          Rp {detailBooking.subtotal.toLocaleString('id-ID')}
                        </p>
                      </div>
                      <div>
                        <span className="text-muted-foreground">Total:</span>
                        <p className="font-semibold text-emerald-600">
                          Rp {detailBooking.total.toLocaleString('id-ID')}
                        </p>
                      </div>
                    </div>

                    {detailBooking.notes && (
                      <div className="mt-3">
                        <span className="text-xs text-muted-foreground">Notes:</span>
                        <p className="text-sm">{detailBooking.notes}</p>
                      </div>
                    )}

                    <div className="mt-4 flex gap-2">
                      {detailBooking.status === 'pending' && (
                        <>
                          <button
                            type="button"
                            onClick={() => confirmBooking.mutate(detailBooking.id)}
                            className="inline-flex h-7 items-center justify-center rounded bg-blue-600 px-3 text-xs text-white hover:bg-blue-700"
                          >
                            Confirm
                          </button>
                          <button
                            type="button"
                            onClick={() => cancelBooking.mutate(detailBooking.id)}
                            className="inline-flex h-7 items-center justify-center rounded bg-red-500 px-3 text-xs text-white hover:bg-red-600"
                          >
                            Cancel
                          </button>
                        </>
                      )}
                      {detailBooking.status === 'confirmed' && (
                        <>
                          <button
                            type="button"
                            onClick={() => completeBooking.mutate(detailBooking.id)}
                            className="inline-flex h-7 items-center justify-center rounded bg-emerald-600 px-3 text-xs text-white hover:bg-emerald-700"
                          >
                            Mark Complete
                          </button>
                          <button
                            type="button"
                            onClick={() => cancelBooking.mutate(detailBooking.id)}
                            className="inline-flex h-7 items-center justify-center rounded bg-red-500 px-3 text-xs text-white hover:bg-red-600"
                          >
                            Cancel
                          </button>
                        </>
                      )}
                    </div>

                    {detailBooking.histories && detailBooking.histories.length > 0 && (
                      <div className="mt-6">
                        <h4 className="text-sm font-medium text-foreground">History</h4>
                        <div className="mt-2 space-y-2">
                          {detailBooking.histories.map((h) => (
                            <div key={h.id} className="flex items-center gap-2 text-xs text-muted-foreground">
                              <span className="font-medium">{h.status_from ?? '-'}</span>
                              <span>→</span>
                              <span className="font-medium">{h.status_to}</span>
                              <span>{new Date(h.created_at).toLocaleDateString('id-ID')}</span>
                            </div>
                          ))}
                        </div>
                      </div>
                    )}
                  </div>
                </div>
              ) : (
                <>
                  {isBookingsLoading ? (
                    <div className="mt-4 flex justify-center">
                      <p className="text-sm text-muted-foreground">Loading bookings...</p>
                    </div>
                  ) : bookings.length > 0 ? (
                    <div className="mt-4 space-y-3">
                      {bookings.map((booking) => (
                        <button
                          key={booking.id}
                          type="button"
                          onClick={() => setSelectedBooking(booking.id)}
                          className="w-full rounded-lg border bg-card p-4 text-left shadow-sm transition-all hover:shadow-md"
                        >
                          <div className="flex items-center justify-between">
                            <div>
                              <h3 className="text-sm font-semibold text-card-foreground">
                                {booking.vendor_name ?? 'Vendor'}
                              </h3>
                              <p className="text-xs text-muted-foreground">
                                {booking.package_name} — {booking.event_date}
                              </p>
                            </div>
                            <span className={`text-xs font-medium ${STATUS_COLORS[booking.status] ?? ''}`}>
                              {STATUS_LABELS[booking.status] ?? booking.status}
                            </span>
                          </div>
                          <div className="mt-1 text-xs text-muted-foreground">
                            Rp {booking.total.toLocaleString('id-ID')}
                          </div>
                        </button>
                      ))}
                    </div>
                  ) : (
                    <div className="mt-8 rounded-lg border bg-card p-12 text-center shadow-sm">
                      <p className="text-muted-foreground">
                        No bookings found. Create a new booking to get started.
                      </p>
                    </div>
                  )}
                </>
              )}
            </>
          )}

          {view.type === 'create' && (
            <div className="mx-auto max-w-lg">
              <h2 className="text-lg font-bold text-foreground">New Booking</h2>
              <p className="mt-1 text-sm text-muted-foreground">
                Fill in the details to book a vendor.
              </p>

              <form
                onSubmit={(e) => {
                  e.preventDefault();
                  handleCreate(formData);
                }}
                className="mt-6 space-y-4"
              >
                <div>
                  <label className="text-xs font-medium text-foreground">Vendor UUID</label>
                  <input
                    type="text"
                    required
                    value={formData.vendor_uuid}
                    onChange={(e) => setFormData((prev) => ({ ...prev, vendor_uuid: e.target.value }))}
                    className="mt-1 h-8 w-full rounded border border-border bg-background px-2 text-xs text-foreground"
                    placeholder="Vendor UUID"
                  />
                </div>
                <div>
                  <label className="text-xs font-medium text-foreground">Package ID</label>
                  <input
                    type="number"
                    required
                    value={formData.package_id || ''}
                    onChange={(e) => setFormData((prev) => ({ ...prev, package_id: Number(e.target.value) }))}
                    className="mt-1 h-8 w-full rounded border border-border bg-background px-2 text-xs text-foreground"
                    placeholder="Package ID"
                  />
                </div>
                <div>
                  <label className="text-xs font-medium text-foreground">Wedding ID</label>
                  <input
                    type="number"
                    required
                    value={formData.wedding_id || ''}
                    onChange={(e) => setFormData((prev) => ({ ...prev, wedding_id: Number(e.target.value) }))}
                    className="mt-1 h-8 w-full rounded border border-border bg-background px-2 text-xs text-foreground"
                    placeholder="Wedding ID"
                  />
                </div>
                <div>
                  <label className="text-xs font-medium text-foreground">Event Date</label>
                  <input
                    type="date"
                    required
                    value={formData.event_date}
                    onChange={(e) => setFormData((prev) => ({ ...prev, event_date: e.target.value }))}
                    className="mt-1 h-8 w-full rounded border border-border bg-background px-2 text-xs text-foreground"
                  />
                </div>
                <div>
                  <label className="text-xs font-medium text-foreground">Notes (optional)</label>
                  <textarea
                    value={formData.notes ?? ''}
                    onChange={(e) => setFormData((prev) => ({ ...prev, notes: e.target.value }))}
                    className="mt-1 w-full rounded border border-border bg-background px-2 py-1 text-xs text-foreground"
                    rows={3}
                  />
                </div>
                <div className="flex gap-2">
                  <button
                    type="submit"
                    disabled={createBooking.isPending}
                    className="inline-flex h-7 items-center justify-center rounded bg-primary px-3 text-xs text-primary-foreground hover:bg-primary/80 disabled:opacity-50"
                  >
                    {createBooking.isPending ? 'Creating...' : 'Create Booking'}
                  </button>
                  <button
                    type="button"
                    onClick={() => setView({ type: 'list' })}
                    className="inline-flex h-7 items-center justify-center rounded border border-border bg-background px-3 text-xs text-foreground hover:bg-muted"
                  >
                    Cancel
                  </button>
                </div>
              </form>
            </div>
          )}

          {view.type === 'calendar' && (
            <>
              <h2 className="text-lg font-bold text-foreground">Booking Calendar</h2>
              <p className="mt-1 text-sm text-muted-foreground">
                Upcoming events for the current month.
              </p>

              {isCalendarLoading ? (
                <div className="mt-4 flex justify-center">
                  <p className="text-sm text-muted-foreground">Loading calendar...</p>
                </div>
              ) : calendarData && calendarData.length > 0 ? (
                <div className="mt-4 space-y-3">
                  {calendarData.map((event) => (
                    <div
                      key={event.id}
                      className="rounded-lg border bg-card p-4 shadow-sm"
                    >
                      <div className="flex items-center justify-between">
                        <div>
                          <h3 className="text-sm font-semibold text-card-foreground">
                            {event.vendor_name}
                          </h3>
                          <p className="text-xs text-muted-foreground">
                            {event.package_name}
                          </p>
                        </div>
                        <div className="text-right">
                          <p className="text-sm font-medium text-foreground">{event.event_date}</p>
                          <span className={`text-xs font-medium ${STATUS_COLORS[event.status] ?? ''}`}>
                            {STATUS_LABELS[event.status] ?? event.status}
                          </span>
                        </div>
                      </div>
                    </div>
                  ))}
                </div>
              ) : (
                <div className="mt-8 rounded-lg border bg-card p-12 text-center shadow-sm">
                  <p className="text-muted-foreground">
                    No upcoming events this month.
                  </p>
                </div>
              )}
            </>
          )}
        </div>
      </main>
    </div>
  );
}
