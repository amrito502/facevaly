import React, { useEffect, useMemo, useState } from 'react'
import { router, useForm, usePage } from '@inertiajs/react'

function formatTime(totalSeconds) {
  const minutes = Math.floor(totalSeconds / 60)
  const seconds = totalSeconds % 60
  return `${minutes}:${String(seconds).padStart(2, '0')}`
}

export default function ForgotPassword({ currentStep = 1, otpExpiresAt = null }) {
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

  const sendForm = useForm({
    country_code: '+880',
    phone: '',
  })

  const otpForm = useForm({
    otp: '',
  })

  const resetForm = useForm({
    password: '',
    password_confirmation: '',
  })

  const sendOtp = (e) => {
    e.preventDefault()
    sendForm.post('/forgot-password/send-otp', {
      preserveScroll: true,
    })
  }

  const verifyOtp = (e) => {
    e.preventDefault()
    otpForm.post('/forgot-password/verify-otp', {
      preserveScroll: true,
    })
  }

  const resendOtp = () => {
    router.post('/forgot-password/resend-otp', {}, { preserveScroll: true })
  }

  const resetPassword = (e) => {
    e.preventDefault()
    resetForm.post('/forgot-password/reset', {
      preserveScroll: true,
    })
  }

  return (
    <div className="min-h-screen flex items-center justify-center p-4 bg-gray-100">
      <div className="w-full max-w-md bg-white rounded-3xl shadow p-6">
        <h1 className="text-2xl font-semibold text-center mb-6">Forgot Password</h1>

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
                value={sendForm.data.country_code}
                onChange={(e) => sendForm.setData('country_code', e.target.value)}
                className="w-28 border rounded-xl px-3 py-3"
              >
                <option value="+880">BD +880</option>
                <option value="+91">IN +91</option>
                <option value="+1">US +1</option>
              </select>

              <input
                type="text"
                placeholder="Phone number"
                value={sendForm.data.phone}
                onChange={(e) => sendForm.setData('phone', e.target.value)}
                className="flex-1 border rounded-xl px-3 py-3"
              />
            </div>

            {sendForm.errors.phone && <p className="text-sm text-red-500 mb-3">{sendForm.errors.phone}</p>}

            <button
              disabled={sendForm.processing}
              className="w-full bg-orange-500 hover:bg-orange-600 text-white rounded-xl py-3 disabled:opacity-50"
            >
              Send OTP
            </button>
          </form>
        )}

        {step === 2 && (
          <form onSubmit={verifyOtp}>
            <input
              type="text"
              placeholder="Enter OTP"
              value={otpForm.data.otp}
              onChange={(e) => otpForm.setData('otp', e.target.value)}
              className="w-full border rounded-xl px-3 py-3 mb-4"
            />

            {otpForm.errors.otp && <p className="text-sm text-red-500 mb-3">{otpForm.errors.otp}</p>}

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
              disabled={otpForm.processing}
              className="w-full bg-orange-500 hover:bg-orange-600 text-white rounded-xl py-3 disabled:opacity-50"
            >
              Verify OTP
            </button>
          </form>
        )}

        {step === 3 && (
          <form onSubmit={resetPassword}>
            <input
              type="password"
              placeholder="New password"
              value={resetForm.data.password}
              onChange={(e) => resetForm.setData('password', e.target.value)}
              className="w-full border rounded-xl px-3 py-3 mb-4"
            />

            <input
              type="password"
              placeholder="Confirm new password"
              value={resetForm.data.password_confirmation}
              onChange={(e) => resetForm.setData('password_confirmation', e.target.value)}
              className="w-full border rounded-xl px-3 py-3 mb-4"
            />

            {resetForm.errors.password && <p className="text-sm text-red-500 mb-3">{resetForm.errors.password}</p>}

            <button
              disabled={resetForm.processing}
              className="w-full bg-orange-500 hover:bg-orange-600 text-white rounded-xl py-3 disabled:opacity-50"
            >
              Reset Password
            </button>
          </form>
        )}
      </div>
    </div>
  )
}
