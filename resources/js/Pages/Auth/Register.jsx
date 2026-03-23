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

  const styles = {
     page: {
    minHeight: '100vh',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    padding: '24px',
    background: 'linear-gradient(135deg, #fff7ed 0%, #ffedd5 35%, #fde68a 100%)',
    fontFamily: "'Inter', sans-serif",
  },
    card: {
      width: '100%',
      maxWidth: '460px',
      background: 'rgba(255,255,255,0.88)',
      backdropFilter: 'blur(14px)',
      WebkitBackdropFilter: 'blur(14px)',
      borderRadius: '26px',
      padding: '32px 28px',
      boxShadow: '0 20px 60px rgba(0,0,0,0.12)',
      border: '1px solid rgba(255,255,255,0.65)',
    },
    headerWrap: {
      textAlign: 'center',
      marginBottom: '26px',
    },
    stepBadge: {
      display: 'inline-block',
      background: 'rgba(249,115,22,0.12)',
      color: '#ea580c',
      padding: '6px 14px',
      borderRadius: '999px',
      fontSize: '12px',
      fontWeight: '700',
      marginBottom: '12px',
    },
    title: {
      fontSize: '30px',
      fontWeight: '700',
      color: '#111827',
      margin: '0 0 8px 0',
    },
    subtitle: {
      fontSize: '14px',
      color: '#6b7280',
      margin: 0,
    },
    progressWrap: {
      display: 'flex',
      gap: '8px',
      marginTop: '18px',
      justifyContent: 'center',
    },
    progressBar: (active) => ({
      width: '56px',
      height: '8px',
      borderRadius: '999px',
      background: active ? 'linear-gradient(135deg, #f97316 0%, #ea580c 100%)' : '#fed7aa',
      transition: 'all 0.25s ease',
    }),
    alertSuccess: {
      marginBottom: '16px',
      borderRadius: '14px',
      background: '#dcfce7',
      color: '#166534',
      padding: '12px 14px',
      fontSize: '14px',
      border: '1px solid #bbf7d0',
    },
    alertError: {
      marginBottom: '16px',
      borderRadius: '14px',
      background: '#fee2e2',
      color: '#991b1b',
      padding: '12px 14px',
      fontSize: '14px',
      border: '1px solid #fecaca',
    },
    label: {
      display: 'block',
      fontSize: '13px',
      fontWeight: '600',
      color: '#374151',
      marginBottom: '8px',
    },
    row: {
      display: 'flex',
      gap: '12px',
      marginBottom: '18px',
    },
    selectSmall: {
      width: '110px',
      border: '1px solid #e5e7eb',
      borderRadius: '14px',
      padding: '14px 12px',
      fontSize: '15px',
      outline: 'none',
      background: '#fff',
      color: '#111827',
      boxShadow: 'inset 0 1px 2px rgba(0,0,0,0.03)',
    },
    input: {
      width: '100%',
      border: '1px solid #e5e7eb',
      borderRadius: '14px',
      padding: '14px 16px',
      fontSize: '15px',
      outline: 'none',
      background: '#fff',
      color: '#111827',
      boxSizing: 'border-box',
      boxShadow: 'inset 0 1px 2px rgba(0,0,0,0.03)',
      marginBottom: '16px',
    },
    select: {
      width: '100%',
      border: '1px solid #e5e7eb',
      borderRadius: '14px',
      padding: '14px 16px',
      fontSize: '15px',
      outline: 'none',
      background: '#fff',
      color: '#111827',
      boxSizing: 'border-box',
      boxShadow: 'inset 0 1px 2px rgba(0,0,0,0.03)',
      marginBottom: '16px',
    },
    errorText: {
      fontSize: '13px',
      color: '#dc2626',
      marginTop: '-6px',
      marginBottom: '14px',
    },
    errorGroup: {
      fontSize: '13px',
      color: '#dc2626',
      marginTop: '-4px',
      marginBottom: '14px',
      lineHeight: '1.6',
    },
    timerBox: {
      marginBottom: '16px',
      background: '#fff7ed',
      border: '1px solid #fdba74',
      color: '#9a3412',
      padding: '12px 14px',
      borderRadius: '14px',
      textAlign: 'center',
      fontSize: '14px',
    },
    resendWrap: {
      marginBottom: '16px',
      textAlign: 'center',
    },
    resendBtn: {
      background: 'transparent',
      border: 'none',
      color: '#2563eb',
      fontSize: '14px',
      fontWeight: '600',
      cursor: canResend ? 'pointer' : 'not-allowed',
      padding: 0,
    },
    button: (processing) => ({
      width: '100%',
      background: processing
        ? '#fdba74'
        : 'linear-gradient(135deg, #f97316 0%, #ea580c 100%)',
      color: '#fff',
      border: 'none',
      borderRadius: '14px',
      padding: '14px',
      fontSize: '16px',
      fontWeight: '700',
      cursor: processing ? 'not-allowed' : 'pointer',
      boxShadow: '0 12px 24px rgba(249,115,22,0.28)',
      transition: 'all 0.25s ease',
    }),
    footerText: {
      textAlign: 'center',
      fontSize: '14px',
      color: '#6b7280',
      marginTop: '18px',
      lineHeight: '1.6',
    },
    link: {
      color: '#2563eb',
      textDecoration: 'none',
      fontWeight: '600',
    },
  }

  return (
    <div style={styles.page}>
      <div style={styles.card}>
        <div style={styles.headerWrap}>
          <div style={styles.stepBadge}>Step {step} of 4</div>
          <h1 style={styles.title}>Create Account</h1>
          <p style={styles.subtitle}>
            {step === 1 && 'Start with your phone number'}
            {step === 2 && 'Verify the OTP sent to your phone'}
            {step === 3 && 'Set a secure password'}
            {step === 4 && 'Complete your personal information'}
          </p>

          <div style={styles.progressWrap}>
            {[1, 2, 3, 4].map((item) => (
              <div key={item} style={styles.progressBar(step >= item)} />
            ))}
          </div>
        </div>

        {flash.success && <div style={styles.alertSuccess}>{flash.success}</div>}
        {flash.error && <div style={styles.alertError}>{flash.error}</div>}

        {step === 1 && (
          <form onSubmit={sendOtp}>
            <label style={styles.label}>Phone Number</label>
            <div style={styles.row}>
              <select
                value={step1.data.country_code}
                onChange={(e) => step1.setData('country_code', e.target.value)}
                style={styles.selectSmall}
              >
                <option value="+880">BD +880</option>
                <option value="+91">IN +91</option>
                <option value="+1">US +1</option>
              </select>

              <input
                type="text"
                placeholder="Enter your phone number"
                value={step1.data.phone}
                onChange={(e) => step1.setData('phone', e.target.value)}
                style={{ ...styles.input, marginBottom: 0 }}
              />
            </div>

            {step1.errors.phone && <p style={styles.errorText}>{step1.errors.phone}</p>}

            <button
              type="submit"
              disabled={step1.processing}
              style={styles.button(step1.processing)}
            >
              {step1.processing ? 'Sending...' : 'Send code via SMS'}
            </button>

            <p style={styles.footerText}>
              Already have an account?{' '}
              <Link href="/login" style={styles.link}>
                Log In Now
              </Link>
            </p>
          </form>
        )}

        {step === 2 && (
          <form onSubmit={verifyOtp}>
            <label style={styles.label}>OTP Code</label>
            <input
              type="text"
              placeholder="Enter OTP"
              value={step2.data.otp}
              onChange={(e) => step2.setData('otp', e.target.value)}
              style={styles.input}
            />

            {step2.errors.otp && <p style={styles.errorText}>{step2.errors.otp}</p>}

            {secondsLeft > 0 ? (
              <div style={styles.timerBox}>
                Resend OTP in <strong style={{ color: '#ea580c' }}>{formatTime(secondsLeft)}</strong>
              </div>
            ) : (
              <div style={styles.resendWrap}>
                <button
                  type="button"
                  onClick={resendOtp}
                  style={styles.resendBtn}
                  disabled={!canResend}
                >
                  Resend OTP
                </button>
              </div>
            )}

            <button
              type="submit"
              disabled={step2.processing}
              style={styles.button(step2.processing)}
            >
              {step2.processing ? 'Verifying...' : 'Verify OTP'}
            </button>
          </form>
        )}

        {step === 3 && (
          <form onSubmit={savePassword}>
            <label style={styles.label}>Password</label>
            <input
              type="password"
              placeholder="Enter your password"
              value={step3.data.password}
              onChange={(e) => step3.setData('password', e.target.value)}
              style={styles.input}
            />

            <label style={styles.label}>Confirm Password</label>
            <input
              type="password"
              placeholder="Confirm your password"
              value={step3.data.password_confirmation}
              onChange={(e) => step3.setData('password_confirmation', e.target.value)}
              style={styles.input}
            />

            {step3.errors.password && <p style={styles.errorText}>{step3.errors.password}</p>}

            <button
              type="submit"
              disabled={step3.processing}
              style={styles.button(step3.processing)}
            >
              {step3.processing ? 'Saving...' : 'Continue'}
            </button>
          </form>
        )}

        {step === 4 && (
          <form onSubmit={finishRegister}>
            <label style={styles.label}>Full Name</label>
            <input
              type="text"
              placeholder="Enter your full name"
              value={step4.data.full_name}
              onChange={(e) => step4.setData('full_name', e.target.value)}
              style={styles.input}
            />

            <label style={styles.label}>Date of Birth</label>
            <input
              type="date"
              value={step4.data.date_of_birth}
              onChange={(e) => step4.setData('date_of_birth', e.target.value)}
              style={styles.input}
            />

            <label style={styles.label}>Gender</label>
            <select
              value={step4.data.gender}
              onChange={(e) => step4.setData('gender', e.target.value)}
              style={styles.select}
            >
              <option value="">Select gender</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="other">Other</option>
            </select>

            {(step4.errors.full_name || step4.errors.date_of_birth || step4.errors.gender) && (
              <div style={styles.errorGroup}>
                {step4.errors.full_name && <div>{step4.errors.full_name}</div>}
                {step4.errors.date_of_birth && <div>{step4.errors.date_of_birth}</div>}
                {step4.errors.gender && <div>{step4.errors.gender}</div>}
              </div>
            )}

            <button
              type="submit"
              disabled={step4.processing}
              style={styles.button(step4.processing)}
            >
              {step4.processing ? 'Creating...' : 'Create Account'}
            </button>
          </form>
        )}
      </div>
    </div>
  )
}
