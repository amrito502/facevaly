import { Link } from '@inertiajs/react'

export default function Welcome() {
  return (
    <div className="flex min-h-screen items-center justify-center bg-gradient-to-br from-slate-50 via-white to-slate-100 px-4">
      <div className="w-full max-w-md rounded-3xl border border-slate-200 bg-white p-8 shadow-xl">
        <div className="text-center">
          <h1 className="text-3xl font-bold tracking-tight text-slate-800">
            Welcome
          </h1>
          <p className="mt-2 text-sm text-slate-500">
            Login or create a new account to continue
          </p>
        </div>

        <div className="mt-8 flex flex-col gap-4 sm:flex-row">
          <Link
            href="/login"
            className="flex-1 rounded-xl bg-blue-600 px-6 py-3 text-center text-sm font-semibold text-white shadow-md transition duration-200 hover:-translate-y-0.5 hover:bg-blue-700 hover:shadow-lg"
          >
            Login
          </Link>

          <Link
            href="/register"
            className="flex-1 rounded-xl border border-slate-300 bg-white px-6 py-3 text-center text-sm font-semibold text-slate-700 transition duration-200 hover:-translate-y-0.5 hover:border-green-500 hover:bg-green-50 hover:text-green-700"
          >
            Register
          </Link>
        </div>
      </div>
    </div>
  )
}
