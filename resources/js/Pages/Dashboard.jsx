import React from "react";
import AppLayout from "@/Layouts/AppLayout";
import { router } from '@inertiajs/react'

export default function Dashboard() {
  return (
    <div>
      <h3>this is a desktop pages</h3>
      <h1>Dashboard Page</h1>
      <button
        onClick={() => router.post('/logout')}
        className="bg-red-500 text-white px-4 py-2 rounded-lg"
      >
        Logout
      </button>


    </div>
  );
}

Dashboard.layout = (page) => <AppLayout>{page}</AppLayout>;