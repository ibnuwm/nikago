"use client"

import Link from "next/link"
import { Heart } from "lucide-react"
import { WeddingRingIcon } from "@/components/icons"

interface AuthLayoutProps {
  title?: string
  description?: string
  children: React.ReactNode
}

export function AuthLayout({ title, description, children }: AuthLayoutProps) {
  return (
    <div className="flex min-h-screen">
      {/* Left: Branding */}
      <div className="relative hidden w-1/2 overflow-hidden bg-gradient-to-br from-pink-50 via-rose-50 to-fuchsia-50 lg:flex lg:flex-col lg:items-center lg:justify-center">
        <div className="absolute inset-0 bg-[url('/images/wedding-pattern.svg')] opacity-[0.03]" />

        <div className="relative z-10 flex flex-col items-center px-12 text-center">
          <div className="mb-8 flex h-20 w-20 items-center justify-center rounded-2xl bg-gradient-to-br from-pink-500 to-rose-500 shadow-lg shadow-pink-500/25">
            <WeddingRingIcon className="h-10 w-10 text-white" />
          </div>

          <h2 className="mb-4 text-3xl font-bold tracking-tight text-gray-900">
            Begin Your Wedding Journey
          </h2>
          <p className="mb-10 max-w-md text-lg leading-relaxed text-gray-600">
            Plan, personalize, and share every moment with your guests.
            Your dream wedding starts here.
          </p>

          <div className="flex items-center gap-2 rounded-full bg-white/60 px-5 py-2.5 backdrop-blur-sm">
            <Heart className="h-4 w-4 fill-rose-500 text-rose-500" />
            <span className="text-sm font-medium text-gray-700">
              Trusted by 10,000+ happy couples
            </span>
          </div>
        </div>
      </div>

      {/* Right: Form */}
      <div className="flex flex-1 flex-col px-6 py-12 sm:px-12 lg:px-16">
        <div className="mb-10 flex items-center justify-center gap-2 lg:hidden">
          <Link href="/" className="flex items-center gap-2">
            <div className="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-pink-500 to-rose-500">
              <WeddingRingIcon className="h-5 w-5 text-white" />
            </div>
            <span className="text-xl font-bold text-gray-900">Nikago</span>
          </Link>
        </div>

        <div className="mx-auto flex w-full max-w-sm flex-1 flex-col justify-center">
          {(title || description) && (
            <div className="mb-8">
              {title && (
                <h1 className="mb-2 text-2xl font-bold tracking-tight text-gray-900">
                  {title}
                </h1>
              )}
              {description && (
                <p className="text-sm text-muted-foreground">{description}</p>
              )}
            </div>
          )}
          {children}
        </div>
      </div>
    </div>
  )
}
