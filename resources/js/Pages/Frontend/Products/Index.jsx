import React, { useState } from "react";
import { Link, router } from "@inertiajs/react";
import AppLayout from "@/Layouts/AppLayout";

/* ⭐ Rating Component */
function StarRating() {
  return (
    <div className="flex items-center gap-0.5 text-sm text-yellow-400">
      <span>★</span>
      <span>★</span>
      <span>★</span>
      <span>★</span>
      <span>★</span>
    </div>
  );
}

/* 🛒 Product Card */
function ProductCard({ product }) {
  const finalPrice = product.sale_price ?? product.price;

  return (
    <Link
      href={`/products/${product.slug}`}
      className="group block overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-md"
    >
      {/* Image */}
      <div className="aspect-square overflow-hidden bg-gray-100">
        <img
          loading="lazy"
          src={
            product.image ||
            "https://via.placeholder.com/400x400?text=No+Image"
          }
          alt={product.name}
          className="h-full w-full object-cover transition duration-300 group-hover:scale-105"
        />
      </div>

      {/* Content */}
      <div className="p-3">
        <h3 className="line-clamp-2 min-h-[48px] text-[15px] font-medium text-gray-800">
          {product.name}
        </h3>

        {/* Price */}
        <div className="mt-2 flex items-center gap-2">
          <span className="text-lg font-bold text-orange-500">
            ৳{finalPrice}
          </span>

          {product.sale_price && product.discount_percent ? (
            <>
              <span className="text-sm text-gray-400 line-through">
                ৳{product.price}
              </span>
              <span className="text-sm text-gray-500">
                -{product.discount_percent}%
              </span>
            </>
          ) : null}
        </div>

        {/* Rating */}
        <div className="mt-2 flex items-center gap-1">
          <StarRating />
          <span className="text-xs text-gray-500">
            ({product.reviews_count ?? 0})
          </span>
        </div>
      </div>
    </Link>
  );
}

/* 📦 Main Page */
function Index({ products, filters }) {
  const [search, setSearch] = useState(filters?.q || "");

  const handleSearch = (e) => {
    e.preventDefault();

    router.get(
      "/products",
      { q: search },
      {
        preserveState: true,
        replace: true,
      }
    );
  };

  return (
    <div className="min-h-screen bg-gray-50 py-8">
      <div className="mx-auto max-w-7xl px-4">
        {/* Header */}
        <div className="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
          <h1 className="text-2xl font-bold text-gray-900">Products</h1>

          {/* Search */}
          <form onSubmit={handleSearch} className="flex w-full max-w-md gap-2">
            <input
              type="text"
              placeholder="Search products..."
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              className="w-full rounded-lg border border-gray-300 px-4 py-2 outline-none focus:border-orange-400"
            />
            <button
              type="submit"
              className="rounded-lg bg-orange-500 px-4 py-2 font-medium text-white hover:bg-orange-600"
            >
              Search
            </button>
          </form>
        </div>

        {/* Products */}
        {products?.data?.length > 0 ? (
          <div className="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
            {products.data.map((product) => (
              <ProductCard key={product.id} product={product} />
            ))}
          </div>
        ) : (
          <div className="rounded-xl bg-white p-10 text-center text-gray-500 shadow-sm">
            No products found.
          </div>
        )}

        {/* Pagination */}
        {products?.links?.length > 0 && (
          <div className="mt-8 flex flex-wrap gap-2">
            {products.links.map((link, index) => (
              <button
                key={index}
                disabled={!link.url}
                onClick={() => link.url && router.visit(link.url)}
                dangerouslySetInnerHTML={{ __html: link.label }}
                className={`rounded-md px-3 py-2 text-sm ${
                  link.active
                    ? "bg-orange-500 text-white"
                    : "border border-gray-300 bg-white text-gray-700"
                } ${!link.url ? "cursor-not-allowed opacity-50" : ""}`}
              />
            ))}
          </div>
        )}
      </div>
    </div>
  );
}

/* Layout */
Index.layout = (page) => <AppLayout>{page}</AppLayout>;

export default Index;