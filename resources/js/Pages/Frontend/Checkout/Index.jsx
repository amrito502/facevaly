import React, { useMemo, useState } from "react";
import { router } from "@inertiajs/react";
import AppLayout from "@/Layouts/AppLayout";
import toast from "react-hot-toast";

function Index({
  cartItems = [],
  shippingRates = [],
  summary = {},
  defaultAddress = null,
}) {
  const [form, setForm] = useState({
    name: defaultAddress?.name || "",
    phone: defaultAddress?.phone || "",
    full_address: defaultAddress?.full_address || "",
    payment_method: "sslcommerz",
    shipping_rate_id: shippingRates[0]?.id || "",
    coupon_code: "",
    save_address: true,
  });

  const [orderSummary, setOrderSummary] = useState({
    subtotal: Number(summary?.subtotal || 0),
    discount: Number(summary?.discount || 0),
    shipping_cost: Number(summary?.shipping_cost || 0),
    tax: Number(summary?.tax || 0),
    total: Number(summary?.total || 0),
  });

  const selectedShipping = useMemo(() => {
    return shippingRates.find(
      (rate) => Number(rate.id) === Number(form.shipping_rate_id)
    );
  }, [shippingRates, form.shipping_rate_id]);

  const groupedItems = useMemo(() => {
    return cartItems.reduce((acc, item) => {
      const key = item.shop_name || "Top Fair";
      if (!acc[key]) acc[key] = [];
      acc[key].push(item);
      return acc;
    }, {});
  }, [cartItems]);

  const updateField = (key, value) => {
    setForm((prev) => ({ ...prev, [key]: value }));
  };

  const recalculateForShipping = (shippingId) => {
    const shipping = shippingRates.find((rate) => Number(rate.id) === Number(shippingId));
    const shippingCost = Number(shipping?.shipping_cost || 0);

    setOrderSummary((prev) => ({
      ...prev,
      shipping_cost: shippingCost,
      total:
        Number(prev.subtotal) -
        Number(prev.discount) +
        shippingCost +
        Number(prev.tax || 0),
    }));
  };

  const handleShippingChange = (id) => {
    updateField("shipping_rate_id", id);
    recalculateForShipping(id);
  };

  const applyCoupon = async () => {
    if (!form.coupon_code) {
      toast.error("Enter coupon code");
      return;
    }

    try {
      const response = await fetch("/checkout/apply-coupon", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
          "X-CSRF-TOKEN":
            document.querySelector('meta[name="csrf-token"]')?.content || "",
          "X-Requested-With": "XMLHttpRequest",
        },
        credentials: "same-origin",
        body: JSON.stringify({
          coupon_code: form.coupon_code,
          shipping_rate_id: form.shipping_rate_id,
        }),
      });

      const result = await response.json();

      if (!response.ok || !result.success) {
        throw new Error(result.message || "Coupon apply failed");
      }

      setOrderSummary({
        subtotal: Number(result.subtotal || 0),
        discount: Number(result.discount || 0),
        shipping_cost: Number(result.shipping_cost || 0),
        tax: Number(result.tax || 0),
        total: Number(result.total || 0),
      });

      toast.success(result.message || "Coupon applied");
    } catch (error) {
      toast.error(error.message || "Coupon apply failed");
    }
  };

  const placeOrder = () => {
    if (!form.name || !form.phone || !form.full_address) {
      toast.error("Name, phone and address are required");
      return;
    }

    router.post("/checkout/place-order", form, {
      preserveScroll: true,
      onSuccess: () => {
        toast.success("Order placed successfully");
      },
      onError: (errors) => {
        const firstError = Object.values(errors)[0];
        toast.error(firstError || "Order failed");
      },
    });
  };

  return (
    <div className="min-h-screen bg-[#f3f4f7] py-3">
      <div className="mx-auto grid max-w-7xl grid-cols-1 gap-6 px-4 lg:grid-cols-[1fr_420px]">
        <div className="rounded-md bg-white p-6">
          <h1 className="mb-4 text-4xl font-semibold text-gray-800">Checkout</h1>
          <div className="mb-5 border-b"></div>

          <h2 className="mb-4 text-3xl font-medium text-gray-900">Shipping Address</h2>

          <div className="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
              <label className="mb-2 block text-lg text-gray-800">Your Name *</label>
              <input
                type="text"
                value={form.name}
                onChange={(e) => updateField("name", e.target.value)}
                placeholder="Enter Name"
                className="w-full rounded-md border border-gray-300 px-4 py-3 outline-none focus:border-teal-500"
              />
            </div>

            <div>
              <label className="mb-2 block text-lg text-gray-800">Phone Number *</label>
              <input
                type="text"
                value={form.phone}
                onChange={(e) => updateField("phone", e.target.value)}
                placeholder="Enter Phone Number"
                className="w-full rounded-md border border-gray-300 px-4 py-3 outline-none focus:border-teal-500"
              />
            </div>
          </div>

          <div className="mt-4">
            <label className="mb-2 block text-lg text-gray-800">Address *</label>
            <textarea
              rows="3"
              value={form.full_address}
              onChange={(e) => updateField("full_address", e.target.value)}
              placeholder="Enter Address e.g. House 12, Road 4, Banani, Dhaka 1213"
              className="w-full rounded-md border border-gray-300 px-4 py-3 outline-none focus:border-teal-500"
            />
          </div>

          <div className="my-6 border-b"></div>

          <h2 className="mb-4 text-3xl font-semibold text-gray-900">
            Items ({cartItems.length})
          </h2>

          <div className="space-y-5">
            {Object.entries(groupedItems).map(([shopName, items]) => (
              <div key={shopName}>
                <div className="mb-2 flex items-center gap-3 bg-slate-100 px-4 py-3 text-lg font-medium text-gray-800">
                  <span>{shopName}</span>
                </div>

                {items.map((item) => (
                  <div
                    key={item.cart_id}
                    className="flex items-start justify-between border-b py-4"
                  >
                    <div className="flex gap-4">
                      <img
                        src={item.image}
                        alt={item.name}
                        className="h-16 w-16 rounded border object-cover"
                      />
                      <div>
                        <h3 className="text-2xl text-gray-900">{item.name}</h3>
                      </div>
                    </div>

                    <div className="text-right">
                      <div className="text-lg text-gray-700">QTY: {item.quantity}</div>
                      <div className="text-3xl font-semibold text-gray-900">
                        ৳{Number(item.line_total).toFixed(0)}
                      </div>
                    </div>
                  </div>
                ))}
              </div>
            ))}
          </div>
        </div>

        <div className="rounded-md bg-white p-6">
          <h2 className="mb-4 text-4xl font-medium text-gray-800">Payment Option</h2>

          <div className="space-y-3">
            <label className="flex cursor-pointer items-center justify-between rounded-md border px-4 py-4">
              <div className="flex items-center gap-3">
                <input
                  type="radio"
                  checked={form.payment_method === "cod"}
                  onChange={() => updateField("payment_method", "cod")}
                />
                <span className="text-xl">Cash on Delivery</span>
              </div>
              <span>💵</span>
            </label>

            <label className="flex cursor-pointer items-center justify-between rounded-md border border-teal-500 bg-teal-50 px-4 py-4">
              <div className="flex items-center gap-3">
                <input
                  type="radio"
                  checked={form.payment_method === "sslcommerz"}
                  onChange={() => updateField("payment_method", "sslcommerz")}
                />
                <span className="text-xl">SSLCommerz</span>
              </div>
              <span className="text-sm">VISA / Master / bKash</span>
            </label>
          </div>

          <div className="my-5 border-b"></div>

          <div className="rounded-md bg-slate-100 p-4">
            <h3 className="mb-4 text-2xl font-semibold">Shipping Fee</h3>

            <div className="space-y-3">
              {shippingRates.map((rate) => (
                <label
                  key={rate.id}
                  className="flex cursor-pointer items-start justify-between"
                >
                  <div className="flex gap-3">
                    <input
                      type="radio"
                      checked={Number(form.shipping_rate_id) === Number(rate.id)}
                      onChange={() => handleShippingChange(rate.id)}
                    />
                    <div>
                      <div className="text-xl text-gray-900">{rate.location}</div>
                    </div>
                  </div>
                  <div className="text-xl font-medium">৳{Number(rate.shipping_cost).toFixed(0)}</div>
                </label>
              ))}
            </div>
          </div>

          <div className="mt-5">
            <h3 className="mb-4 text-4xl font-medium text-gray-900">Order summary</h3>

            <div className="space-y-3 text-xl">
              <div className="flex justify-between">
                <span>Price ({cartItems.length} items)</span>
                <span>৳{orderSummary.subtotal.toFixed(0)}</span>
              </div>

              <div className="flex justify-between">
                <span>Discount</span>
                <span className="text-green-500">-৳{orderSummary.discount.toFixed(0)}</span>
              </div>

              <div className="flex justify-between">
                <span>Shipping fee</span>
                <span>৳{orderSummary.shipping_cost.toFixed(0)}</span>
              </div>
            </div>

            <div className="mt-4 flex">
              <input
                type="text"
                value={form.coupon_code}
                onChange={(e) => updateField("coupon_code", e.target.value)}
                placeholder="Store / Packly Coupon"
                className="w-full rounded-l-md border border-gray-300 px-4 py-3 outline-none"
              />
              <button
                type="button"
                onClick={applyCoupon}
                className="rounded-r-md bg-teal-500 px-6 py-3 font-medium text-white"
              >
                Apply
              </button>
            </div>

            <div className="my-5 border-b"></div>

            <div className="mb-5 flex items-center justify-between text-3xl font-bold">
              <span>Total</span>
              <span>৳{orderSummary.total.toFixed(0)}</span>
            </div>

            <label className="mb-5 flex items-start gap-3 text-base text-gray-700">
              <input type="checkbox" defaultChecked />
              <span>
                I have read and agree to the Terms and Conditions, Privacy Policy,
                and Refund and Return Policy.
              </span>
            </label>

            <button
              type="button"
              onClick={placeOrder}
              className="w-full rounded-md bg-slate-400 px-4 py-4 text-2xl font-semibold text-white hover:bg-slate-500"
            >
              Place Order
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}

Index.layout = (page) => <AppLayout>{page}</AppLayout>;

export default Index;