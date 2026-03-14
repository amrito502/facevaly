import React from 'react'
import { router } from '@inertiajs/react'

export default function Dashboard() {
  return (
    <div className="min-h-screen flex flex-col items-center justify-center gap-4">
      <h1 className="text-3xl font-bold">Dashboard</h1>

      <button
        onClick={() => router.post('/logout')}
        className="bg-red-500 text-white px-4 py-2 rounded-lg"
      >
        Logout
      </button>
    </div>
  )
}
