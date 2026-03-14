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

  return (
    <div className="min-h-screen flex items-center justify-center p-4 bg-gray-100">
      <form onSubmit={submit} className="w-full max-w-md bg-white rounded-2xl shadow p-6">
        <h1 className="text-2xl font-semibold text-center mb-6">Log In</h1>

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

        <div className="flex gap-2 mb-4">
          <select
            value={data.country_code}
            onChange={(e) => setData('country_code', e.target.value)}
            className="w-28 border rounded-lg px-3 py-3"
          >
            <option value="+880">BD +880</option>
            <option value="+91">IN +91</option>
            <option value="+1">US +1</option>
          </select>

          <input
            type="text"
            placeholder="Please enter your phone number"
            value={data.phone}
            onChange={(e) => setData('phone', e.target.value)}
            className="flex-1 border rounded-lg px-3 py-3"
          />
        </div>

        {errors.phone && <p className="text-sm text-red-500 mb-3">{errors.phone}</p>}

        <input
          type="password"
          placeholder="Password"
          value={data.password}
          onChange={(e) => setData('password', e.target.value)}
          className="w-full border rounded-lg px-3 py-3 mb-4"
        />

        {errors.password && <p className="text-sm text-red-500 mb-3">{errors.password}</p>}

        <button
          type="submit"
          disabled={processing}
          className="w-full bg-orange-500 hover:bg-orange-600 text-white rounded-lg py-3 disabled:opacity-50"
        >
          Login
        </button>

        <div className="flex justify-between mt-4 text-sm">
          <Link href="/register" className="text-blue-600">Create account</Link>
          <Link href="/forgot-password" className="text-blue-600">Forgot password?</Link>
        </div>
      </form>
    </div>
  )
}
