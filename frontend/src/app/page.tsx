import { Button } from "@/components/ui/button";
import Link from "next/link";

export default function Home() {
  return (
    <div className="flex flex-col flex-1 items-center justify-center min-h-screen bg-gradient-to-b from-pink-50 to-white">
      <main className="flex flex-1 w-full max-w-4xl flex-col items-center justify-center px-6 py-24">
        <div className="text-center space-y-8">
          <h1 className="text-5xl font-bold tracking-tight text-gray-900 sm:text-6xl">
            Nikago
          </h1>
          <p className="text-xl text-gray-600 max-w-2xl mx-auto">
            Your Wedding Super App. Plan, organize, and celebrate your perfect day
            with our comprehensive wedding management platform.
          </p>
          <div className="flex gap-4 justify-center">
            <Link href="/login">
              <Button size="lg">Get Started</Button>
            </Link>
            <Link href="/register">
              <Button variant="outline" size="lg">
                Create Account
              </Button>
            </Link>
          </div>
        </div>
      </main>
    </div>
  );
}
