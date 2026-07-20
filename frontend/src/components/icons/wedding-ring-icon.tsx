export function WeddingRingIcon({ className, ...props }: React.SVGProps<SVGSVGElement>) {
  return (
    <svg
      viewBox="0 0 24 24"
      fill="none"
      stroke="currentColor"
      strokeWidth={1.5}
      strokeLinecap="round"
      strokeLinejoin="round"
      className={className}
      {...props}
    >
      <circle cx="12" cy="14" r="7" />
      <path d="M9 7.5C9 6.12 10.34 5 12 5s3 1.12 3 2.5c0 1.5-1.5 2.5-3 4.5" />
      <circle cx="12" cy="5" r="1" fill="currentColor" stroke="none" />
    </svg>
  )
}
