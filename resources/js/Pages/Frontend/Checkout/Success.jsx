import React from "react";
import { Link } from "@inertiajs/react";
import AppLayout from "@/Layouts/AppLayout";

function Success({ order = null }) {
  return (
    <div className="min-h-screen bg-[#eef2f5] px-4 py-5">
      <div className="mx-auto max-w-[1280px] rounded-sm bg-white px-6 py-14 md:px-10 md:py-16">
        <div className="flex flex-col items-center justify-center text-center">
          <div className="mb-8 flex h-28 w-28 items-center justify-center rounded-full bg-green-500 relative">
            <svg
              className="h-12 w-12 text-white"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              strokeWidth="3"
              strokeLinecap="round"
              strokeLinejoin="round"
            >
              <path d="M20 6L9 17l-5-5" />
            </svg>

            <span className="absolute -top-4 left-1/2 h-3 w-1 -translate-x-1/2 rounded bg-green-500"></span>
            <span className="absolute -bottom-4 left-1/2 h-3 w-1 -translate-x-1/2 rounded bg-green-500"></span>
            <span className="absolute left-[-14px] top-1/2 h-1 w-3 -translate-y-1/2 rounded bg-green-500"></span>
            <span className="absolute right-[-14px] top-1/2 h-1 w-3 -translate-y-1/2 rounded bg-green-500"></span>

            <span className="absolute left-[10px] top-[10px] h-1 w-3 rotate-45 rounded bg-green-500"></span>
            <span className="absolute right-[10px] top-[10px] h-1 w-3 -rotate-45 rounded bg-green-500"></span>
            <span className="absolute bottom-[10px] left-[10px] h-1 w-3 -rotate-45 rounded bg-green-500"></span>
            <span className="absolute bottom-[10px] right-[10px] h-1 w-3 rotate-45 rounded bg-green-500"></span>
          </div>

          <h1 className="mb-4 text-3xl font-bold text-[#0f2747] md:text-5xl">
            Your order is successfully placed!
          </h1>

          <p className="max-w-[720px] text-lg leading-9 text-[#334e68] md:text-[20px]">
            Your order has been placed successfully. Go to
            <br />
            My Order to track your order
          </p>

          {order?.order_number ? (
            <p className="mt-4 text-base font-medium text-gray-500">
              Order No: {order.order_number}
            </p>
          ) : null}

          <div className="mt-10 flex flex-col items-center gap-4 sm:flex-row">
            <Link
              href="/products"
              className="inline-flex min-w-[190px] items-center justify-center rounded-md border border-[#11b69a] bg-white px-8 py-4 text-lg font-medium text-[#11b69a] transition hover:bg-[#f0fffb]"
            >
              Continue Shopping
            </Link>

            <Link
              href="/"
              className="inline-flex min-w-[190px] items-center justify-center rounded-md bg-[#11b69a] px-8 py-4 text-lg font-semibold text-white transition hover:bg-[#0da88d]"
            >
              Go To Dashboard
            </Link>
          </div>
        </div>
      </div>
    </div>
  );
}

Success.layout = (page) => <AppLayout>{page}</AppLayout>;

export default Success;