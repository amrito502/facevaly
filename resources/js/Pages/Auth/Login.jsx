import React from 'react'
import { Link, useForm, usePage } from '@inertiajs/react'

export default function Login() {
  const { props } = usePage()
  const flash = props.flash || {}

  const { data, setData, post, processing, errors } = useForm({
    country_code: '+880',
    phone: '',
    password: '',
  })

  const submit = (e) => {
    e.preventDefault()
    post('/login', {
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
      maxWidth: '440px',
      background: 'rgba(255, 255, 255, 0.88)',
      backdropFilter: 'blur(14px)',
      WebkitBackdropFilter: 'blur(14px)',
      borderRadius: '24px',
      padding: '32px 28px',
      boxShadow: '0 20px 60px rgba(0, 0, 0, 0.12)',
      border: '1px solid rgba(255,255,255,0.6)',
    },
    headerWrap: {
      textAlign: 'center',
      marginBottom: '28px',
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
    select: {
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
      transition: 'all 0.2s ease',
    },
    passwordWrap: {
      marginBottom: '18px',
    },
    errorText: {
      fontSize: '13px',
      color: '#dc2626',
      marginTop: '-8px',
      marginBottom: '14px',
    },
    button: {
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
      boxShadow: '0 12px 24px rgba(249, 115, 22, 0.28)',
      transition: 'all 0.25s ease',
    },
    footer: {
      display: 'flex',
      justifyContent: 'space-between',
      alignItems: 'center',
      marginTop: '18px',
      fontSize: '14px',
      gap: '12px',
      flexWrap: 'wrap',
    },
    link: {
      color: '#2563eb',
      textDecoration: 'none',
      fontWeight: '500',
    },
  }

  return (
    <div style={styles.page}>
      <form onSubmit={submit} style={styles.card}>
        <div style={styles.headerWrap}>
          <h1 style={styles.title}>Welcome Back</h1>
          <p style={styles.subtitle}>Login with your phone number and password</p>
        </div>

        {flash.success && (
          <div style={styles.alertSuccess}>
            {flash.success}
          </div>
        )}

        {flash.error && (
          <div style={styles.alertError}>
            {flash.error}
          </div>
        )}

        <label style={styles.label}>Phone Number</label>
        <div style={styles.row}>
          <select
            value={data.country_code}
            onChange={(e) => setData('country_code', e.target.value)}
            style={styles.select}
          >
            <option value="+880">BD +880</option>
            <option value="+91">IN +91</option>
            <option value="+1">US +1</option>
          </select>

          <input
            type="text"
            placeholder="Enter your phone number"
            value={data.phone}
            onChange={(e) => setData('phone', e.target.value)}
            style={styles.input}
          />
        </div>

        {errors.phone && <p style={styles.errorText}>{errors.phone}</p>}

        <div style={styles.passwordWrap}>
          <label style={styles.label}>Password</label>
          <input
            type="password"
            placeholder="Enter your password"
            value={data.password}
            onChange={(e) => setData('password', e.target.value)}
            style={styles.input}
          />
        </div>

        {errors.password && <p style={styles.errorText}>{errors.password}</p>}

        <button
          type="submit"
          disabled={processing}
          style={styles.button}
        >
          {processing ? 'Logging in...' : 'Login'}
        </button>

        <div style={styles.footer}>
          <Link href="/register" style={styles.link}>
            Create account
          </Link>
          <Link href="/forgot-password" style={styles.link}>
            Forgot password?
          </Link>
        </div>
      </form>
    </div>
  )
}
