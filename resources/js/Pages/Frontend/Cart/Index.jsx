import React, { useEffect, useMemo, useRef, useState } from "react";
import { Link } from "@inertiajs/react";
import AppLayout from "@/Layouts/AppLayout";
import toast from "react-hot-toast";

function Index({ cartItems = [] }) {
  const [items, setItems] = useState(cartItems);
  const [selected, setSelected] = useState([]);
  const latestServerItemsRef = useRef(cartItems);
  const silentPendingRef = useRef({});

  useEffect(() => {
    setItems(cartItems);
    latestServerItemsRef.current = cartItems;

    setSelected((prev) => {
      const availableIds = cartItems.map((item) => item.id);
      const kept = prev.filter((id) => availableIds.includes(id));
      return kept.length ? kept : availableIds;
    });
  }, [cartItems]);

  const getCsrfToken = () => {
    return document
      .querySelector('meta[name="csrf-token"]')
      ?.getAttribute("content");
  };

  const apiRequest = async (url, method = "GET", data = null) => {
    const response = await fetch(url, {
      method,
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        "X-CSRF-TOKEN": getCsrfToken(),
        "X-Requested-With": "XMLHttpRequest",
      },
      body: data ? JSON.stringify(data) : null,
      credentials: "same-origin",
    });

    const result = await response.json().catch(() => ({}));

    if (!response.ok || result.success === false) {
      const message =
        result?.message ||
        result?.errors?.quantity?.[0] ||
        "Something went wrong";
      throw new Error(message);
    }

    return result;
  };

  const syncFromServer = (serverItems = []) => {
    setItems(serverItems);
    latestServerItemsRef.current = serverItems;

    setSelected((prev) => {
      const validIds = serverItems.map((item) => item.id);
      return prev.filter((id) => validIds.includes(id));
    });
  };

  const allSelected = items.length > 0 && selected.length === items.length;

  const toggleSelectAll = () => {
    if (allSelected) {
      setSelected([]);
    } else {
      setSelected(items.map((item) => item.id));
    }
  };

  const toggleItem = (id) => {
    setSelected((prev) =>
      prev.includes(id) ? prev.filter((itemId) => itemId !== id) : [...prev, id]
    );
  };

  const changeQty = async (item, type) => {
    if (!item?.id) return;
    if (silentPendingRef.current[`qty-${item.id}`]) return;

    const oldItems = latestServerItemsRef.current;
    const existing = items.find((i) => i.id === item.id);
    if (!existing) return;

    const newQty = type === "inc" ? existing.quantity + 1 : existing.quantity - 1;
    if (newQty < 1) return;

    silentPendingRef.current[`qty-${item.id}`] = true;

    const optimisticItems = items.map((i) =>
      i.id === item.id
        ? {
            ...i,
            quantity: newQty,
            line_total: Number(i.final_price) * newQty,
          }
        : i
    );

    setItems(optimisticItems);

    try {
      const res = await apiRequest(`/cart/${item.id}`, "PATCH", {
        quantity: newQty,
      });

      syncFromServer(res.cartItems || optimisticItems);
      toast.success(res.message || "Cart quantity updated");
    } catch (error) {
      setItems(oldItems);
      toast.error(error.message || "Quantity update failed");
    } finally {
      delete silentPendingRef.current[`qty-${item.id}`];
    }
  };

  const removeItem = async (itemId) => {
    if (!itemId) return;
    if (silentPendingRef.current[`remove-${itemId}`]) return;

    const oldItems = latestServerItemsRef.current;
    silentPendingRef.current[`remove-${itemId}`] = true;

    const optimisticItems = items.filter((item) => item.id !== itemId);
    setItems(optimisticItems);
    setSelected((prev) => prev.filter((id) => id !== itemId));

    try {
      const res = await apiRequest(`/cart/${itemId}`, "DELETE");

      syncFromServer(res.cartItems || optimisticItems);
      toast.success(res.message || "Item removed from cart");
    } catch (error) {
      setItems(oldItems);
      toast.error(error.message || "Remove failed");
    } finally {
      delete silentPendingRef.current[`remove-${itemId}`];
    }
  };

  const removeSelected = async () => {
    if (selected.length === 0) return;
    if (silentPendingRef.current["bulk-remove"]) return;

    const oldItems = latestServerItemsRef.current;
    const selectedIds = [...selected];

    silentPendingRef.current["bulk-remove"] = true;

    const optimisticItems = items.filter((item) => !selectedIds.includes(item.id));
    setItems(optimisticItems);
    setSelected([]);

    try {
      const res = await apiRequest(`/cart`, "DELETE", {
        cart_ids: selectedIds,
      });

      syncFromServer(res.cartItems || optimisticItems);
      toast.success(res.message || "Selected items removed");
    } catch (error) {
      setItems(oldItems);
      setSelected(selectedIds);
      toast.error(error.message || "Bulk remove failed");
    } finally {
      delete silentPendingRef.current["bulk-remove"];
    }
  };

  const selectedItems = useMemo(() => {
    return items.filter((item) => selected.includes(item.id));
  }, [items, selected]);

  const selectedSubTotal = useMemo(() => {
    return selectedItems.reduce((sum, item) => {
      return sum + Number(item.final_price) * Number(item.quantity);
    }, 0);
  }, [selectedItems]);

  return (
    <div className="min-h-screen bg-[#f3f4f7] py-8">
      <div className="mx-auto max-w-7xl px-4">
        <div className="mb-5 flex items-center gap-2 text-sm text-gray-500">
          <Link href="/" className="hover:text-orange-500">
            Home
          </Link>
          <span>›</span>
          <span>My Cart</span>
        </div>

        <div className="grid grid-cols-1 gap-6 lg:grid-cols-[1fr_380px]">
          <div className="rounded-xl bg-white p-4 shadow-sm">
            <div className="mb-4 flex flex-col gap-4 border-b pb-4 md:flex-row md:items-center md:justify-between">
              <h1 className="text-3xl font-bold text-gray-900">
                My Cart ({items.length})
              </h1>

              <div className="flex flex-wrap items-center gap-4">
                <label className="flex cursor-pointer items-center gap-2 text-base text-gray-700">
                  <input
                    type="checkbox"
                    checked={allSelected}
                    onChange={toggleSelectAll}
                    className="h-5 w-5 rounded border-gray-300"
                  />
                  <span>Select All</span>
                </label>

                <button
                  type="button"
                  onClick={() => setSelected([])}
                  className="text-sm text-gray-400 hover:text-gray-600"
                >
                  Clear selection
                </button>

                <button
                  type="button"
                  onClick={removeSelected}
                  disabled={selected.length === 0}
                  className={`rounded-md px-3 py-2 text-sm ${
                    selected.length === 0
                      ? "cursor-not-allowed bg-gray-100 text-gray-400"
                      : "bg-red-50 text-red-600 hover:bg-red-100"
                  }`}
                >
                  Delete Selected
                </button>
              </div>
            </div>

            {items.length > 0 ? (
              <div className="space-y-4">
                {items.map((item) => (
                  <div
                    key={item.id}
                    className="flex flex-col gap-4 rounded-lg border border-gray-200 px-4 py-4 md:flex-row md:items-center md:justify-between"
                  >
                    <div className="flex items-center gap-4">
                      <input
                        type="checkbox"
                        checked={selected.includes(item.id)}
                        onChange={() => toggleItem(item.id)}
                        className="h-5 w-5 rounded border-gray-300"
                      />

                      <img
                        src={item.image}
                        alt={item.name}
                        className="h-20 w-20 rounded-md border object-cover"
                      />

                      <div>
                        <h3 className="text-base font-medium text-gray-800 md:text-lg">
                          {item.name}
                        </h3>

                        <div className="mt-1 text-sm text-gray-500">
                          {item.shop_name}
                        </div>

                        <div className="mt-3 inline-flex items-center rounded-full border border-gray-300 bg-white">
                          <button
                            type="button"
                            onClick={() => changeQty(item, "dec")}
                            className="flex h-10 w-12 items-center justify-center text-xl text-gray-600"
                          >
                            −
                          </button>

                          <span className="flex h-10 min-w-[44px] items-center justify-center text-sm text-gray-800">
                            {item.quantity}
                          </span>

                          <button
                            type="button"
                            onClick={() => changeQty(item, "inc")}
                            className="flex h-10 w-12 items-center justify-center text-xl text-gray-600"
                          >
                            +
                          </button>
                        </div>
                      </div>
                    </div>

                    <div className="flex items-center justify-between gap-4 md:justify-end">
                      <div className="text-right">
                        <div className="text-2xl font-semibold text-gray-900">
                          ৳ {Number(item.final_price).toFixed(2)}
                        </div>

                        {item.sale_price ? (
                          <div className="text-sm text-gray-400 line-through">
                            ৳ {Number(item.price).toFixed(2)}
                          </div>
                        ) : null}

                        <div className="mt-1 text-sm text-gray-500">
                          Line total: ৳ {Number(item.line_total).toFixed(2)}
                        </div>
                      </div>

                      <button
                        type="button"
                        onClick={() => removeItem(item.id)}
                        className="rounded-md px-3 py-2 text-sm text-red-500 hover:bg-red-50"
                      >
                        Remove
                      </button>
                    </div>
                  </div>
                ))}
              </div>
            ) : (
              <div className="rounded-lg border border-gray-200 bg-white p-10 text-center text-gray-500">
                Your cart is empty.
              </div>
            )}
          </div>

          <div className="h-fit rounded-xl bg-white p-6 shadow-sm">
            <h2 className="mb-5 text-2xl font-semibold text-gray-900">
              Order summary
            </h2>

            <div className="space-y-4 text-base">
              <div className="flex items-center justify-between">
                <span className="text-gray-700">
                  Price ({selectedItems.length} items)
                </span>
                <span className="font-medium">
                  ৳ {selectedSubTotal.toFixed(2)}
                </span>
              </div>

              <div className="flex items-center justify-between">
                <span className="text-gray-700">Shipping fee</span>
                <span className="text-blue-500">To be Added</span>
              </div>
            </div>

            <div className="my-6 border-t border-dashed border-gray-300"></div>

            <div className="mb-6 flex items-center justify-between text-2xl font-bold">
              <span>Sub Total</span>
              <span>৳ {selectedSubTotal.toFixed(2)}</span>
            </div>

            <Link
              href="/checkout"
              className="block w-full rounded-lg bg-orange-500 px-4 py-3 text-center text-sm font-medium text-white hover:bg-orange-600"
            >
              Proceed to Checkout
            </Link>
          </div>
        </div>
      </div>
    </div>
  );
}

Index.layout = (page) => <AppLayout>{page}</AppLayout>;

export default Index;