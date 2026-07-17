import Link from 'next/link';
import { Button } from '@/components/ui/button';
import { FaqSection } from '@/features/cms/components/faq-section';

const features = [
  {
    title: 'Guest Management',
    description: 'Track RSVPs, manage seating, and send invitations all in one place.',
  },
  {
    title: 'Budget Tracker',
    description: 'Keep your wedding budget on track with real-time expense tracking.',
  },
  {
    title: 'Vendor Directory',
    description: 'Find and connect with trusted wedding vendors in your area.',
  },
  {
    title: 'Timeline Planner',
    description: 'Create and manage your wedding day timeline with ease.',
  },
  {
    title: 'Wedding Website',
    description: 'Build a beautiful wedding website to share with your guests.',
  },
  {
    title: 'AI Assistant',
    description: 'Get personalized recommendations and planning tips from our AI.',
  },
];

const pricingPlans = [
  {
    name: 'Free',
    price: '$0',
    period: 'forever',
    description: 'Perfect for getting started',
    features: ['1 Wedding', 'Basic Guest List', 'Budget Tracker', 'Community Support'],
    cta: 'Get Started',
    href: '/register',
    highlighted: false,
  },
  {
    name: 'Premium',
    price: '$29',
    period: 'month',
    description: 'Everything you need for your dream wedding',
    features: [
      'Unlimited Weddings',
      'Full Guest Management',
      'Vendor Directory',
      'Wedding Website',
      'AI Assistant',
      'Priority Support',
    ],
    cta: 'Start Free Trial',
    href: '/register',
    highlighted: true,
  },
  {
    name: 'Enterprise',
    price: '$99',
    period: 'month',
    description: 'For wedding planners and businesses',
    features: [
      'Everything in Premium',
      'Multiple Team Members',
      'Client Management',
      'Custom Branding',
      'API Access',
      'Dedicated Support',
    ],
    cta: 'Contact Sales',
    href: '/register',
    highlighted: false,
  },
];

export default function LandingPage() {
  return (
    <div className="flex flex-col min-h-screen">
      {/* Hero Section */}
      <section className="relative overflow-hidden bg-gradient-to-b from-pink-50 to-background py-20 sm:py-32">
        <div className="mx-auto max-w-7xl px-6 lg:px-8">
          <div className="mx-auto max-w-2xl text-center">
            <h1 className="text-4xl font-bold tracking-tight text-foreground sm:text-6xl">
              Plan Your Perfect Wedding
            </h1>
            <p className="mt-6 text-lg leading-8 text-muted-foreground">
              Nikago is your all-in-one wedding planning platform. Organize guests,
              track budgets, find vendors, and create the wedding of your dreams.
            </p>
            <div className="mt-10 flex items-center justify-center gap-x-6">
              <Link href="/register">
                <Button size="lg">Get Started Free</Button>
              </Link>
              <Link href="#features">
                <Button variant="outline" size="lg">
                  Learn More
                </Button>
              </Link>
            </div>
          </div>
        </div>
      </section>

      {/* Features Section */}
      <section id="features" className="py-20 sm:py-32 bg-background">
        <div className="mx-auto max-w-7xl px-6 lg:px-8">
          <div className="mx-auto max-w-2xl text-center">
            <h2 className="text-3xl font-bold tracking-tight text-foreground sm:text-4xl">
              Everything You Need
            </h2>
            <p className="mt-6 text-lg leading-8 text-muted-foreground">
              From guest lists to budget tracking, Nikago has all the tools to make
              your wedding planning stress-free.
            </p>
          </div>
          <div className="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-none">
            <div className="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
              {features.map((feature) => (
                <div
                  key={feature.title}
                  className="rounded-lg border bg-card p-6 shadow-sm"
                >
                  <h3 className="text-lg font-semibold text-card-foreground">
                    {feature.title}
                  </h3>
                  <p className="mt-2 text-sm text-muted-foreground">
                    {feature.description}
                  </p>
                </div>
              ))}
            </div>
          </div>
        </div>
      </section>

      {/* Pricing Section */}
      <section id="pricing" className="py-20 sm:py-32 bg-muted/50">
        <div className="mx-auto max-w-7xl px-6 lg:px-8">
          <div className="mx-auto max-w-2xl text-center">
            <h2 className="text-3xl font-bold tracking-tight text-foreground sm:text-4xl">
              Simple, Transparent Pricing
            </h2>
            <p className="mt-6 text-lg leading-8 text-muted-foreground">
              Choose the plan that fits your needs. Upgrade anytime as your wedding grows.
            </p>
          </div>
          <div className="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-4xl">
            <div className="grid grid-cols-1 gap-8 sm:grid-cols-3">
              {pricingPlans.map((plan) => (
                <div
                  key={plan.name}
                  className={`rounded-lg border p-8 shadow-sm ${
                    plan.highlighted
                      ? 'border-primary bg-card ring-2 ring-primary'
                      : 'bg-card'
                  }`}
                >
                  {plan.highlighted && (
                    <span className="mb-4 inline-block rounded-full bg-primary/10 px-3 py-1 text-xs font-medium text-primary">
                      Most Popular
                    </span>
                  )}
                  <h3 className="text-lg font-semibold text-card-foreground">
                    {plan.name}
                  </h3>
                  <p className="mt-4 flex items-baseline gap-x-2">
                    <span className="text-4xl font-bold text-card-foreground">
                      {plan.price}
                    </span>
                    <span className="text-sm text-muted-foreground">
                      /{plan.period}
                    </span>
                  </p>
                  <p className="mt-2 text-sm text-muted-foreground">{plan.description}</p>
                  <ul className="mt-6 space-y-3">
                    {plan.features.map((feature) => (
                      <li
                        key={feature}
                        className="flex items-center gap-2 text-sm text-muted-foreground"
                      >
                        <svg
                          className="h-4 w-4 shrink-0 text-primary"
                          fill="none"
                          viewBox="0 0 24 24"
                          strokeWidth="2"
                          stroke="currentColor"
                        >
                          <path
                            strokeLinecap="round"
                            strokeLinejoin="round"
                            d="M4.5 12.75l6 6 9-13.5"
                          />
                        </svg>
                        {feature}
                      </li>
                    ))}
                  </ul>
                  <Link href={plan.href} className="mt-8 block">
                    <Button
                      variant={plan.highlighted ? 'default' : 'outline'}
                      className="w-full"
                    >
                      {plan.cta}
                    </Button>
                  </Link>
                </div>
              ))}
            </div>
          </div>
        </div>
      </section>

      {/* FAQ Section */}
      <FaqSection />

      {/* CTA Section */}
      <section className="py-20 sm:py-32 bg-background">
        <div className="mx-auto max-w-7xl px-6 lg:px-8">
          <div className="mx-auto max-w-2xl text-center">
            <h2 className="text-3xl font-bold tracking-tight text-foreground sm:text-4xl">
              Ready to Start Planning?
            </h2>
            <p className="mt-6 text-lg leading-8 text-muted-foreground">
              Join thousands of couples who planned their perfect wedding with Nikago.
              It&apos;s free to get started.
            </p>
            <div className="mt-10 flex items-center justify-center gap-x-6">
              <Link href="/register">
                <Button size="lg">Create Free Account</Button>
              </Link>
            </div>
          </div>
        </div>
      </section>

      {/* Footer */}
      <footer className="border-t bg-muted/50">
        <div className="mx-auto max-w-7xl px-6 py-12 lg:px-8">
          <div className="grid grid-cols-2 gap-8 sm:grid-cols-4">
            <div>
              <h3 className="text-sm font-semibold text-card-foreground">Product</h3>
              <ul className="mt-4 space-y-2">
                <li>
                  <Link
                    href="#features"
                    className="text-sm text-muted-foreground hover:text-card-foreground"
                  >
                    Features
                  </Link>
                </li>
                <li>
                  <Link
                    href="#pricing"
                    className="text-sm text-muted-foreground hover:text-card-foreground"
                  >
                    Pricing
                  </Link>
                </li>
                <li>
                  <Link
                    href="/blog"
                    className="text-sm text-muted-foreground hover:text-card-foreground"
                  >
                    Blog
                  </Link>
                </li>
              </ul>
            </div>
            <div>
              <h3 className="text-sm font-semibold text-card-foreground">Company</h3>
              <ul className="mt-4 space-y-2">
                <li>
                  <Link
                    href="/about"
                    className="text-sm text-muted-foreground hover:text-card-foreground"
                  >
                    About
                  </Link>
                </li>
                <li>
                  <Link
                    href="/contact"
                    className="text-sm text-muted-foreground hover:text-card-foreground"
                  >
                    Contact
                  </Link>
                </li>
              </ul>
            </div>
            <div>
              <h3 className="text-sm font-semibold text-card-foreground">Legal</h3>
              <ul className="mt-4 space-y-2">
                <li>
                  <Link
                    href="/privacy"
                    className="text-sm text-muted-foreground hover:text-card-foreground"
                  >
                    Privacy Policy
                  </Link>
                </li>
                <li>
                  <Link
                    href="/terms"
                    className="text-sm text-muted-foreground hover:text-card-foreground"
                  >
                    Terms of Service
                  </Link>
                </li>
              </ul>
            </div>
            <div>
              <h3 className="text-sm font-semibold text-card-foreground">Support</h3>
              <ul className="mt-4 space-y-2">
                <li>
                  <Link
                    href="/help"
                    className="text-sm text-muted-foreground hover:text-card-foreground"
                  >
                    Help Center
                  </Link>
                </li>
                <li>
                  <Link
                    href="/faq"
                    className="text-sm text-muted-foreground hover:text-card-foreground"
                  >
                    FAQ
                  </Link>
                </li>
              </ul>
            </div>
          </div>
          <div className="mt-8 border-t pt-8">
            <p className="text-center text-sm text-muted-foreground">
              &copy; {new Date().getFullYear()} Nikago. All rights reserved.
            </p>
          </div>
        </div>
      </footer>
    </div>
  );
}
