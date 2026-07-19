"use client"

import { useState } from "react"
import Link from "next/link"
import { useRouter } from "next/navigation"
import { useRegister } from "@/hooks/use-auth"
import { Input } from "@/components/ui/input"
import { Button } from "@/components/ui/button"
import { Eye, EyeOff, Check, ArrowRight } from "lucide-react"
import type { RegisterData } from "@/types"

interface PasswordRule {
  label: string
  test: (pw: string) => boolean
}

const PASSWORD_RULES: PasswordRule[] = [
  { label: "At least 8 characters", test: (pw) => pw.length >= 8 },
  { label: "One uppercase letter", test: (pw) => /[A-Z]/.test(pw) },
  { label: "One lowercase letter", test: (pw) => /[a-z]/.test(pw) },
  { label: "One number", test: (pw) => /[0-9]/.test(pw) },
]

interface FormErrors {
  name?: string[]
  email?: string[]
  password?: string[]
}

export function RegisterForm() {
  const router = useRouter()
  const { mutate: register, isPending } = useRegister()
  const [showPassword, setShowPassword] = useState(false)
  const [errorMessage, setErrorMessage] = useState("")
  const [fieldErrors, setFieldErrors] = useState<FormErrors>({})

  const [formData, setFormData] = useState({
    name: "",
    email: "",
    password: "",
  })

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const { name, value } = e.target
    setFormData((prev) => ({ ...prev, [name]: value }))

    if (fieldErrors[name as keyof FormErrors]) {
      setFieldErrors((prev) => ({ ...prev, [name]: undefined }))
    }
    if (errorMessage) {
      setErrorMessage("")
    }
  }

  const getFirstError = (field: keyof FormErrors): string | undefined => {
    return fieldErrors[field]?.[0]
  }

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault()
    setErrorMessage("")
    setFieldErrors({})

    const payload: RegisterData = {
      name: formData.name,
      email: formData.email,
      password: formData.password,
      password_confirmation: formData.password,
    }

    register(payload, {
      onSuccess: () => {
        router.push("/dashboard")
      },
      onError: (err: unknown) => {
        const axiosErr = err as {
          response?: {
            status?: number
            data?: {
              success?: boolean
              message?: string
              errors?: Record<string, string[]>
              error?: { code?: string; message?: string; errors?: Record<string, string[]> }
            }
          }
        }
        const status = axiosErr.response?.status
        const data = axiosErr.response?.data

        if (status === 422 && data?.errors) {
          setFieldErrors(data.errors)
          setErrorMessage(data.message || "Please fix the errors below.")
        } else if (status === 409) {
          setErrorMessage(
            "An account with this email already exists. Please sign in instead."
          )
        } else if (status === 429) {
          setErrorMessage("Too many attempts. Please try again later.")
        } else {
          setErrorMessage(
            data?.error?.message || data?.message || "Something went wrong. Please try again."
          )
        }
      },
    })
  }

  return (
    <form onSubmit={handleSubmit} className="space-y-5">
      {errorMessage && (
        <div className="rounded-lg border border-destructive/20 bg-destructive/5 px-4 py-3 text-sm text-destructive">
          {errorMessage}
        </div>
      )}

      {/* Name */}
      <div className="space-y-2">
        <label
          htmlFor="name"
          className="text-sm font-medium text-foreground"
        >
          Full Name
        </label>
        <Input
          id="name"
          name="name"
          type="text"
          placeholder="John & Jane"
          value={formData.name}
          onChange={handleChange}
          aria-invalid={!!getFirstError("name")}
          aria-describedby={getFirstError("name") ? "name-error" : undefined}
          disabled={isPending}
          required
        />
        {getFirstError("name") && (
          <p id="name-error" className="text-xs text-destructive">
            {getFirstError("name")}
          </p>
        )}
      </div>

      {/* Email */}
      <div className="space-y-2">
        <label
          htmlFor="email"
          className="text-sm font-medium text-foreground"
        >
          Email Address
        </label>
        <Input
          id="email"
          name="email"
          type="email"
          placeholder="you@example.com"
          value={formData.email}
          onChange={handleChange}
          aria-invalid={!!getFirstError("email")}
          aria-describedby={
            getFirstError("email") ? "email-error" : undefined
          }
          disabled={isPending}
          required
        />
        {getFirstError("email") && (
          <p id="email-error" className="text-xs text-destructive">
            {getFirstError("email")}
          </p>
        )}
      </div>

      {/* Password */}
      <div className="space-y-2">
        <label
          htmlFor="password"
          className="text-sm font-medium text-foreground"
        >
          Password
        </label>
        <div className="relative">
          <Input
            id="password"
            name="password"
            type={showPassword ? "text" : "password"}
            placeholder="Create a strong password"
            value={formData.password}
            onChange={handleChange}
            aria-invalid={!!getFirstError("password")}
            aria-describedby={
              getFirstError("password") ? "password-error" : undefined
            }
            disabled={isPending}
            required
          />
          <button
            type="button"
            onClick={() => setShowPassword(!showPassword)}
            className="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground transition-colors hover:text-foreground"
            aria-label={showPassword ? "Hide password" : "Show password"}
            tabIndex={-1}
          >
            {showPassword ? (
              <EyeOff className="h-4 w-4" />
            ) : (
              <Eye className="h-4 w-4" />
            )}
          </button>
        </div>
        {getFirstError("password") && (
          <p id="password-error" className="text-xs text-destructive">
            {getFirstError("password")}
          </p>
        )}
        {formData.password.length > 0 && (
          <div className="space-y-1 pt-1">
            {PASSWORD_RULES.map((rule) => (
              <div key={rule.label} className="flex items-center gap-2">
                <div
                  className={`flex h-4 w-4 items-center justify-center rounded-full transition-colors ${
                    rule.test(formData.password)
                      ? "bg-emerald-500 text-white"
                      : "bg-muted text-muted-foreground"
                  }`}
                >
                  {rule.test(formData.password) && (
                    <Check className="h-3 w-3" />
                  )}
                </div>
                <span
                  className={`text-xs ${
                    rule.test(formData.password)
                      ? "text-emerald-600"
                      : "text-muted-foreground"
                  }`}
                >
                  {rule.label}
                </span>
              </div>
            ))}
          </div>
        )}
      </div>

      {/* Submit */}
      <Button type="submit" className="w-full" size="lg" disabled={isPending}>
        {isPending ? (
          <span className="flex items-center gap-2">
            <span className="h-4 w-4 animate-spin rounded-full border-2 border-current border-t-transparent" />
            Creating account...
          </span>
        ) : (
          <span className="flex items-center gap-2">
            Create My Free Account
            <ArrowRight className="h-4 w-4" />
          </span>
        )}
      </Button>

      <p className="text-center text-xs text-muted-foreground">
        Free forever. No credit card required.
      </p>

      <div className="pt-2 text-center text-sm text-muted-foreground">
        Already have an account?{" "}
        <Link
          href="/login"
          className="font-medium text-foreground underline-offset-4 hover:underline"
        >
          Sign in
        </Link>
      </div>
    </form>
  )
}
