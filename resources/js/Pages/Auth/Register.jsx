import React, { useEffect, useMemo, useState } from 'react'
import { Link, router, useForm, usePage } from '@inertiajs/react'

function formatTime(totalSeconds) {
  const minutes = Math.floor(totalSeconds / 60)
  const seconds = totalSeconds % 60
  return `${minutes}:${String(seconds).padStart(2, '0')}`
}

export default function Register({ currentStep = 1, otpExpiresAt = null }) {
  const { props } = usePage()
  const [step, setStep] = useState(currentStep)
  const [secondsLeft, setSecondsLeft] = useState(0)

  useEffect(() => {
    setStep(currentStep)
  }, [currentStep])

  useEffect(() => {
    if (!otpExpiresAt) {
      setSecondsLeft(0)
      return
    }

    const updateTimer = () => {
      const expireMs = new Date(otpExpiresAt).getTime()
      const nowMs = new Date().getTime()
      const diff = Math.max(0, Math.floor((expireMs - nowMs) / 1000))
      setSecondsLeft(diff)
    }

    updateTimer()
    const interval = setInterval(updateTimer, 1000)

    return () => clearInterval(interval)
  }, [otpExpiresAt])

  const flash = props.flash || {}

  const canResend = useMemo(() => step === 2 && secondsLeft <= 0, [step, secondsLeft])

  const step1 = useForm({
    country_code: '+880',
    phone: '',
  })

  const step2 = useForm({
    otp: '',
  })

  const step3 = useForm({
    password: '',
    password_confirmation: '',
  })

  const step4 = useForm({
    full_name: '',
    date_of_birth: '',
    gender: '',
  })

  const sendOtp = (e) => {
    e.preventDefault()
    step1.post('/register/step-1', {
      preserveScroll: true,
    })
  }

  const verifyOtp = (e) => {
    e.preventDefault()
    step2.post('/register/step-2', {
      preserveScroll: true,
    })
  }

  const resendOtp = () => {
    router.post('/register/resend-otp', {}, { preserveScroll: true })
  }

  const savePassword = (e) => {
    e.preventDefault()
    step3.post('/register/step-3', {
      preserveScroll: true,
    })
  }

  const finishRegister = (e) => {
    e.preventDefault()
    step4.post('/register/step-4', {
      preserveScroll: true,
    })
  }

  return (
    <div className="min-h-screen flex items-center justify-center p-4 bg-gray-100">
      <div className="w-full max-w-md bg-white rounded-3xl shadow p-6">
        <h1 className="text-2xl font-semibold text-center mb-6">Sign up</h1>

        {flash.success && (
          <div className="mb-4 rounded-lg bg-green-100 text-green-700 px-4 py-3 text-sm">
            {flash.success}
          </div>
        )}

        {flash.error && (
          <div className="mb-4 rounded-lg bg-red-100 text-red-700 px-4 py-3 text-sm">
            {flash.error}
          </div>
        )}

        {step === 1 && (
          <form onSubmit={sendOtp}>
            <div className="flex gap-2 mb-4">
              <select
                value={step1.data.country_code}
                onChange={(e) => step1.setData('country_code', e.target.value)}
                className="w-28 border rounded-xl px-3 py-3"
              >
                <option value="+880">BD +880</option>
                <option value="+91">IN +91</option>
                <option value="+1">US +1</option>
              </select>

              <input
                type="text"
                placeholder="Please enter your phone number"
                value={step1.data.phone}
                onChange={(e) => step1.setData('phone', e.target.value)}
                className="flex-1 border rounded-xl px-3 py-3"
              />
            </div>

            {step1.errors.phone && <p className="text-sm text-red-500 mb-3">{step1.errors.phone}</p>}

            <button
              disabled={step1.processing}
              className="w-full bg-orange-500 hover:bg-orange-600 text-white rounded-xl py-3 disabled:opacity-50"
            >
              Send code via SMS
            </button>

            <p className="text-center text-sm mt-4">
              Already have an account? <Link href="/login" className="text-blue-600">Log In Now</Link>
            </p>
          </form>
        )}

        {step === 2 && (
          <form onSubmit={verifyOtp}>
            <input
              type="text"
              placeholder="Enter OTP"
              value={step2.data.otp}
              onChange={(e) => step2.setData('otp', e.target.value)}
              className="w-full border rounded-xl px-3 py-3 mb-4"
            />

            {step2.errors.otp && <p className="text-sm text-red-500 mb-3">{step2.errors.otp}</p>}

            {secondsLeft > 0 ? (
              <div className="mb-4 text-sm text-gray-600 text-center">
                Resend OTP in <span className="font-semibold text-orange-500">{formatTime(secondsLeft)}</span>
              </div>
            ) : (
              <div className="mb-4 text-center">
                <button
                  type="button"
                  onClick={resendOtp}
                  className="text-sm font-medium text-blue-600 hover:underline"
                >
                  Resend OTP
                </button>
              </div>
            )}

            <button
              disabled={step2.processing}
              className="w-full bg-orange-500 hover:bg-orange-600 text-white rounded-xl py-3 disabled:opacity-50"
            >
              Verify OTP
            </button>
          </form>
        )}

        {step === 3 && (
          <form onSubmit={savePassword}>
            <input
              type="password"
              placeholder="Password"
              value={step3.data.password}
              onChange={(e) => step3.setData('password', e.target.value)}
              className="w-full border rounded-xl px-3 py-3 mb-4"
            />

            <input
              type="password"
              placeholder="Confirm Password"
              value={step3.data.password_confirmation}
              onChange={(e) => step3.setData('password_confirmation', e.target.value)}
              className="w-full border rounded-xl px-3 py-3 mb-4"
            />

            {step3.errors.password && <p className="text-sm text-red-500 mb-3">{step3.errors.password}</p>}

            <button
              disabled={step3.processing}
              className="w-full bg-orange-500 hover:bg-orange-600 text-white rounded-xl py-3 disabled:opacity-50"
            >
              Continue
            </button>
          </form>
        )}

        {step === 4 && (
          <form onSubmit={finishRegister}>
            <input
              type="text"
              placeholder="Full name"
              value={step4.data.full_name}
              onChange={(e) => step4.setData('full_name', e.target.value)}
              className="w-full border rounded-xl px-3 py-3 mb-4"
            />

            <input
              type="date"
              value={step4.data.date_of_birth}
              onChange={(e) => step4.setData('date_of_birth', e.target.value)}
              className="w-full border rounded-xl px-3 py-3 mb-4"
            />

            <select
              value={step4.data.gender}
              onChange={(e) => step4.setData('gender', e.target.value)}
              className="w-full border rounded-xl px-3 py-3 mb-4"
            >
              <option value="">Select gender</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="other">Other</option>
            </select>

            {(step4.errors.full_name || step4.errors.date_of_birth || step4.errors.gender) && (
              <div className="text-sm text-red-500 mb-3 space-y-1">
                {step4.errors.full_name && <p>{step4.errors.full_name}</p>}
                {step4.errors.date_of_birth && <p>{step4.errors.date_of_birth}</p>}
                {step4.errors.gender && <p>{step4.errors.gender}</p>}
              </div>
            )}

            <button
              disabled={step4.processing}
              className="w-full bg-orange-500 hover:bg-orange-600 text-white rounded-xl py-3 disabled:opacity-50"
            >
              Create Account
            </button>
          </form>
        )}
      </div>
    </div>
  )
}
