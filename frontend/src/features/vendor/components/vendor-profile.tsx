'use client';

import type { Vendor } from '@/types';

interface VendorProfileProps {
  vendor: Vendor;
  onBack?: () => void;
}

function formatOperatingHours(hours: Record<string, { open: string; close: string }> | null) {
  if (!hours) return null;
  const dayNames: Record<string, string> = {
    monday: 'Senin', tuesday: 'Selasa', wednesday: 'Rabu', thursday: 'Kamis',
    friday: 'Jumat', saturday: 'Sabtu', sunday: 'Minggu',
  };
  return Object.entries(hours).map(([day, times]) => ({
    day: dayNames[day] ?? day,
    hours: times.open && times.close ? `${times.open} - ${times.close}` : 'Tutup',
  }));
}

function formatSocialMedia(social: Record<string, string> | null) {
  if (!social) return [];
  return Object.entries(social).map(([platform, url]) => ({ platform, url }));
}

export function VendorProfile({ vendor, onBack }: VendorProfileProps) {
  const hours = formatOperatingHours(vendor.operating_hours);
  const socialLinks = formatSocialMedia(vendor.social_media);

  return (
    <div className="min-h-screen bg-background">
      <header className="border-b bg-card">
        <div className="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
          {onBack && (
            <button
              type="button"
              onClick={onBack}
              className="inline-flex h-7 items-center justify-center gap-1 rounded-md border border-border bg-background px-2.5 text-xs font-medium text-foreground transition-all hover:bg-muted"
            >
              ← Back
            </button>
          )}
          <h1 className="text-xl font-bold text-card-foreground">{vendor.business_name}</h1>
          <div className="w-20" />
        </div>
      </header>

      <main className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 space-y-8">
        {vendor.cover && (
          <div className="relative h-48 overflow-hidden rounded-lg sm:h-64">
            <img
              src={vendor.cover}
              alt={`${vendor.business_name} cover`}
              className="h-full w-full object-cover"
            />
          </div>
        )}

        <div className={`flex flex-col gap-6 sm:flex-row ${vendor.cover ? '' : 'pt-4'}`}>
          {vendor.logo && (
            <div className="shrink-0">
              <img
                src={vendor.logo}
                alt={`${vendor.business_name} logo`}
                className="h-24 w-24 rounded-lg border bg-card object-cover sm:h-32 sm:w-32"
              />
            </div>
          )}
          <div className="flex-1 min-w-0">
            <div className="flex flex-wrap items-center gap-2">
              <h2 className="text-2xl font-bold text-foreground">{vendor.business_name}</h2>
              {vendor.verified_at && (
                <span className="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-xs font-medium text-emerald-700">
                  ✓ Verified
                </span>
              )}
              <span className={`inline-flex items-center gap-1 rounded-full px-2 py-0.5 text-xs font-medium ${
                vendor.status === 'active' ? 'bg-green-50 text-green-700' : 'bg-muted text-muted-foreground'
              }`}>
                {vendor.status}
              </span>
            </div>
            {(vendor.city || vendor.province) && (
              <p className="mt-1 text-sm text-muted-foreground">
                {[vendor.city, vendor.province].filter(Boolean).join(', ')}
              </p>
            )}
            {vendor.rating > 0 && (
              <p className="mt-1 text-sm text-muted-foreground">
                ★ {vendor.rating.toFixed(1)} ({vendor.total_review} reviews)
              </p>
            )}
          </div>
        </div>

        <div className="grid grid-cols-1 gap-6 lg:grid-cols-3">
          <div className="lg:col-span-2 space-y-6">
            {vendor.description && (
              <section className="rounded-lg border bg-card p-5 shadow-sm">
                <h3 className="text-sm font-semibold text-foreground mb-2">About</h3>
                <p className="text-sm text-muted-foreground whitespace-pre-line">{vendor.description}</p>
              </section>
            )}

            {vendor.services && vendor.services.length > 0 && (
              <section className="rounded-lg border bg-card p-5 shadow-sm">
                <h3 className="text-sm font-semibold text-foreground mb-3">Services</h3>
                <div className="grid grid-cols-1 gap-3 sm:grid-cols-2">
                  {vendor.services.map((svc) => (
                    <div key={svc.id} className="rounded-md border bg-muted/30 p-3">
                      <p className="text-sm font-medium text-foreground">{svc.name}</p>
                      {svc.description && (
                        <p className="mt-0.5 text-xs text-muted-foreground">{svc.description}</p>
                      )}
                      {svc.starting_price && (
                        <p className="mt-1 text-xs font-medium text-emerald-600">
                          Start from Rp {svc.starting_price.toLocaleString('id-ID')}
                        </p>
                      )}
                    </div>
                  ))}
                </div>
              </section>
            )}

            {vendor.galleries && vendor.galleries.length > 0 && (
              <section className="rounded-lg border bg-card p-5 shadow-sm">
                <h3 className="text-sm font-semibold text-foreground mb-3">Gallery</h3>
                <div className="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                  {vendor.galleries.map((g) => (
                    <div key={g.id} className="group relative overflow-hidden rounded-md">
                      <img
                        src={g.image_url}
                        alt={g.caption ?? ''}
                        className="h-40 w-full object-cover transition-transform group-hover:scale-105"
                      />
                      {g.caption && (
                        <div className="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/60 to-transparent p-2">
                          <p className="text-xs text-white truncate">{g.caption}</p>
                        </div>
                      )}
                    </div>
                  ))}
                </div>
              </section>
            )}

            {vendor.portfolios && vendor.portfolios.length > 0 && (
              <section className="rounded-lg border bg-card p-5 shadow-sm">
                <h3 className="text-sm font-semibold text-foreground mb-3">Portfolio</h3>
                <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                  {vendor.portfolios.map((p) => (
                    <div key={p.id} className="group rounded-md border bg-muted/30 overflow-hidden">
                      <img
                        src={p.image_url}
                        alt={p.title}
                        className="h-48 w-full object-cover transition-transform group-hover:scale-105"
                      />
                      <div className="p-3">
                        <p className="text-sm font-medium text-foreground">{p.title}</p>
                        {p.description && (
                          <p className="mt-0.5 text-xs text-muted-foreground line-clamp-2">{p.description}</p>
                        )}
                      </div>
                    </div>
                  ))}
                </div>
              </section>
            )}

            {vendor.packages && vendor.packages.length > 0 && (
              <section className="rounded-lg border bg-card p-5 shadow-sm">
                <h3 className="text-sm font-semibold text-foreground mb-3">Packages & Pricing</h3>
                <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                  {vendor.packages.map((pkg) => (
                    <div key={pkg.id} className="rounded-md border bg-muted/30 p-4 flex flex-col">
                      <p className="text-sm font-semibold text-foreground">{pkg.name}</p>
                      {pkg.description && (
                        <p className="mt-1 text-xs text-muted-foreground">{pkg.description}</p>
                      )}
                      <p className="mt-2 text-lg font-bold text-emerald-600">
                        Rp {pkg.price.toLocaleString('id-ID')}
                      </p>
                      {pkg.inclusions && pkg.inclusions.length > 0 && (
                        <ul className="mt-3 space-y-1 text-xs text-muted-foreground">
                          {pkg.inclusions.map((inc, i) => (
                            <li key={i} className="flex items-center gap-1">✓ {inc}</li>
                          ))}
                        </ul>
                      )}
                    </div>
                  ))}
                </div>
              </section>
            )}
          </div>

          <div className="space-y-4">
            <section className="rounded-lg border bg-card p-5 shadow-sm">
              <h3 className="text-sm font-semibold text-foreground mb-3">Contact</h3>
              <div className="space-y-2 text-sm text-muted-foreground">
                {vendor.phone && (
                  <p><span className="font-medium text-foreground">Phone:</span> {vendor.phone}</p>
                )}
                {vendor.email && (
                  <p><span className="font-medium text-foreground">Email:</span> {vendor.email}</p>
                )}
                {vendor.address && (
                  <p><span className="font-medium text-foreground">Address:</span> {vendor.address}</p>
                )}
              </div>
            </section>

            {hours && hours.length > 0 && (
              <section className="rounded-lg border bg-card p-5 shadow-sm">
                <h3 className="text-sm font-semibold text-foreground mb-3">Operating Hours</h3>
                <div className="space-y-1 text-sm text-muted-foreground">
                  {hours.map((h) => (
                    <div key={h.day} className="flex justify-between">
                      <span className="font-medium text-foreground">{h.day}</span>
                      <span>{h.hours}</span>
                    </div>
                  ))}
                </div>
              </section>
            )}

            {socialLinks.length > 0 && (
              <section className="rounded-lg border bg-card p-5 shadow-sm">
                <h3 className="text-sm font-semibold text-foreground mb-3">Social Media</h3>
                <div className="space-y-2 text-sm">
                  {socialLinks.map(({ platform, url }) => (
                    <a
                      key={platform}
                      href={url}
                      target="_blank"
                      rel="noopener noreferrer"
                      className="flex items-center gap-2 text-muted-foreground hover:text-primary transition-colors"
                    >
                      <span className="font-medium capitalize">{platform}</span>
                      <span className="truncate text-xs">{url}</span>
                    </a>
                  ))}
                </div>
              </section>
            )}
          </div>
        </div>
      </main>
    </div>
  );
}
