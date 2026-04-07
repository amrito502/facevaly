import React from "react";
import { Head, Link } from "@inertiajs/react";

function StatusBadge({ status }) {
    const map = {
        active: "bg-emerald-100 text-emerald-700",
        pending: "bg-amber-100 text-amber-700",
        draft: "bg-slate-100 text-slate-700",
        inactive: "bg-red-100 text-red-700",
        rejected: "bg-rose-100 text-rose-700",
    };

    return (
        <span className={`rounded-full px-3 py-1 text-xs font-semibold ${map[status] || "bg-slate-100 text-slate-700"}`}>
            {status}
        </span>
    );
}

function Pagination({ links }) {
    if (!links || links.length <= 3) return null;

    return (
        <div className="mt-6 flex flex-wrap items-center gap-2">
            {links.map((link, index) => {
                const isDisabled = link.url === null;

                if (isDisabled) {
                    return (
                        <span
                            key={index}
                            className="rounded-md border border-slate-200 px-3 py-2 text-sm text-slate-400"
                            dangerouslySetInnerHTML={{ __html: link.label }}
                        />
                    );
                }

                return (
                    <Link
                        key={index}
                        href={link.url}
                        className={`rounded-md border px-3 py-2 text-sm ${
                            link.active
                                ? "border-emerald-500 bg-emerald-500 text-white"
                                : "border-slate-200 bg-white text-slate-700 hover:bg-slate-50"
                        }`}
                        dangerouslySetInnerHTML={{ __html: link.label }}
                    />
                );
            })}
        </div>
    );
}

export default function Index({ products }) {
    return (
        <>
            <Head title="Product List" />

            <div className="min-h-screen bg-slate-100 p-6">
                <div className="mx-auto max-w-[1300px]">
                    <div className="mb-6 flex items-center justify-between">
                        <div>
                            <h1 className="text-3xl font-bold text-slate-800">Product List</h1>
                            <p className="mt-1 text-sm text-slate-500">All seller products are shown here.</p>
                        </div>

                        <Link
                            href="/seller/products/create"
                            className="rounded-md bg-emerald-500 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-600"
                        >
                            + Add Product
                        </Link>
                    </div>

                    <div className="overflow-hidden rounded-xl bg-white shadow-sm">
                        <div className="overflow-x-auto">
                            <table className="min-w-full text-left">
                                <thead className="bg-slate-50">
                                    <tr className="border-b border-slate-200">
                                        <th className="px-4 py-4 text-sm font-semibold text-slate-700">Image</th>
                                        <th className="px-4 py-4 text-sm font-semibold text-slate-700">Product</th>
                                        <th className="px-4 py-4 text-sm font-semibold text-slate-700">SKU</th>
                                        <th className="px-4 py-4 text-sm font-semibold text-slate-700">Category</th>
                                        <th className="px-4 py-4 text-sm font-semibold text-slate-700">Brand</th>
                                        <th className="px-4 py-4 text-sm font-semibold text-slate-700">Type</th>
                                        <th className="px-4 py-4 text-sm font-semibold text-slate-700">Price</th>
                                        <th className="px-4 py-4 text-sm font-semibold text-slate-700">Stock</th>
                                        <th className="px-4 py-4 text-sm font-semibold text-slate-700">Status</th>
                                        <th className="px-4 py-4 text-sm font-semibold text-slate-700">Featured</th>
                                        <th className="px-4 py-4 text-sm font-semibold text-slate-700">Published</th>
                                        <th className="px-4 py-4 text-sm font-semibold text-slate-700">Actions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    {products.data.length > 0 ? (
                                        products.data.map((product) => (
                                            <tr key={product.id} className="border-b border-slate-100 hover:bg-slate-50">
                                                <td className="px-4 py-4">
                                                    {product.thumbnail ? (
                                                        <img
                                                            src={product.thumbnail}
                                                            alt={product.name}
                                                            className="h-14 w-14 rounded-md border object-cover"
                                                        />
                                                    ) : (
                                                        <div className="flex h-14 w-14 items-center justify-center rounded-md border bg-slate-100 text-xs text-slate-400">
                                                            No Img
                                                        </div>
                                                    )}
                                                </td>

                                                <td className="px-4 py-4">
                                                    <div className="font-semibold text-slate-800">{product.name}</div>
                                                    <div className="mt-1 text-xs text-slate-500">#{product.id}</div>
                                                </td>

                                                <td className="px-4 py-4 text-sm text-slate-700">
                                                    {product.sku || "-"}
                                                </td>

                                                <td className="px-4 py-4 text-sm text-slate-700">
                                                    {product.category || "-"}
                                                </td>

                                                <td className="px-4 py-4 text-sm text-slate-700">
                                                    {product.brand || "-"}
                                                </td>

                                                <td className="px-4 py-4 text-sm capitalize text-slate-700">
                                                    {product.product_type}
                                                </td>

                                                <td className="px-4 py-4 text-sm text-slate-700">
                                                    <div className="font-semibold">
                                                        ৳{product.discounted_price || product.regular_price || 0}
                                                    </div>
                                                    {product.discounted_price && product.regular_price && (
                                                        <div className="text-xs text-slate-400 line-through">
                                                            ৳{product.regular_price}
                                                        </div>
                                                    )}
                                                </td>

                                                <td className="px-4 py-4 text-sm text-slate-700">
                                                    {product.stock_qty}
                                                </td>

                                                <td className="px-4 py-4">
                                                    <StatusBadge status={product.status} />
                                                </td>

                                                <td className="px-4 py-4 text-sm text-slate-700">
                                                    {product.is_featured ? "Yes" : "No"}
                                                </td>

                                                <td className="px-4 py-4 text-sm text-slate-700">
                                                    {product.published_at || "-"}
                                                </td>

                                                <td className="px-4 py-4">
                                                    <div className="flex items-center gap-2">
                                                        <button
                                                            type="button"
                                                            className="rounded-md bg-blue-50 px-3 py-2 text-xs font-medium text-blue-600 hover:bg-blue-100"
                                                        >
                                                            View
                                                        </button>

                                                        <button
                                                            type="button"
                                                            className="rounded-md bg-amber-50 px-3 py-2 text-xs font-medium text-amber-600 hover:bg-amber-100"
                                                        >
                                                            Edit
                                                        </button>

                                                        <button
                                                            type="button"
                                                            className="rounded-md bg-red-50 px-3 py-2 text-xs font-medium text-red-600 hover:bg-red-100"
                                                        >
                                                            Delete
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        ))
                                    ) : (
                                        <tr>
                                            <td colSpan="12" className="px-4 py-10 text-center text-sm text-slate-500">
                                                No products found.
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                        </div>

                        <div className="px-6 pb-6">
                            <Pagination links={products.links} />
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}