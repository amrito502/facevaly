import React, { useMemo, useState } from "react";
import { Link, router } from "@inertiajs/react";
import AppLayout from "@/Layouts/AppLayout";

function StarRow({ count = 5 }) {
  return (
    <div className="flex items-center gap-1 text-pink-500">
      {Array.from({ length: count }).map((_, i) => (
        <span key={i}>★</span>
      ))}
    </div>
  );
}

function DotIndicators({ count = 3, active = 0 }) {
  return (
    <div className="mb-2 flex items-center justify-center gap-1">
      {Array.from({ length: count }).map((_, i) => (
        <span
          key={i}
          className={`inline-block h-[5px] w-[5px] rounded-full ${
            i === active ? "bg-pink-500" : "bg-pink-200"
          }`}
        />
      ))}
    </div>
  );
}

function SimilarProductCard({ product }) {
  const finalPrice = product.sale_price ?? product.price;

  return (
    <Link
      href={`/products/${product.slug}`}
      className="overflow-hidden rounded-xl border border-pink-100 bg-white shadow-sm transition hover:shadow-md"
    >
      <div className="aspect-square bg-gray-100">
        <img
          src={product.image || "https://via.placeholder.com/400"}
          className="h-full w-full object-cover"
          alt={product.name}
        />
      </div>

      <div className="p-3">
        <DotIndicators />

        <h3 className="line-clamp-2 text-sm text-gray-700">{product.name}</h3>

        <div className="mt-2 flex items-center gap-2">
          <span className="text-lg font-bold text-pink-600">
            ৳{Number(finalPrice).toFixed(2)}
          </span>

          {product.sale_price && product.discount_percent ? (
            <>
              <span className="text-sm text-gray-400 line-through">
                ৳{Number(product.price).toFixed(2)}
              </span>
              <span className="text-sm text-orange-400">
                -{product.discount_percent}%
              </span>
            </>
          ) : null}
        </div>
      </div>
    </Link>
  );
}

function Show({ product, similarProducts = [] }) {
  const images = product?.images?.length
    ? product.images
    : ["https://via.placeholder.com/800x800?text=No+Image"];

  const [activeImage, setActiveImage] = useState(images[0]);
  const [qty, setQty] = useState(1);
  const [activeTab, setActiveTab] = useState("description");
  const [expanded, setExpanded] = useState(false);
  const [adding, setAdding] = useState(false);

  const finalPrice = product?.sale_price ?? product?.price ?? 0;
  const oldPrice = product?.sale_price ? product?.price : null;

  const discountText = useMemo(() => {
    if (!product?.sale_price || !product?.price) return null;
    const percent = Math.round(
      ((product.price - product.sale_price) / product.price) * 100
    );
    return percent > 0 ? `${percent}% OFF` : null;
  }, [product]);

  const reviewCount = product?.reviews_count ?? 0;
  const stock = product?.stock_qty ?? 0;

  const description =
    product?.description || "<p>No description available</p>";

  const handleAddToCart = () => {
    if (adding) return;

    setAdding(true);

    router.post(
      "/cart",
      {
        product_id: product.id,
        quantity: qty,
      },
      {
        preserveScroll: true,
        preserveState: true,
        only: ["cartCount", "flash", "errors"],
        onFinish: () => setAdding(false),
      }
    );
  };

  const handleBuyNow = () => {
    if (adding) return;

    setAdding(true);

    router.post(
      "/cart",
      {
        product_id: product.id,
        quantity: qty,
      },
      {
        preserveScroll: true,
        preserveState: true,
        only: ["cartCount", "flash", "errors"],
        onSuccess: () => {
          router.visit("/cart");
        },
        onFinish: () => setAdding(false),
      }
    );
  };

  return (
    <div className="min-h-screen bg-gray-50 py-6">
      <div className="mx-auto max-w-7xl px-4">
        <div className="grid grid-cols-1 gap-8 lg:grid-cols-2">
          <div className="flex gap-4">
            <div className="flex flex-col gap-2">
              {images.map((img, i) => (
                <img
                  key={i}
                  src={img}
                  onClick={() => setActiveImage(img)}
                  className={`h-16 w-16 cursor-pointer object-cover border ${
                    activeImage === img
                      ? "border-pink-500"
                      : "border-gray-200"
                  }`}
                  alt={`Preview ${i + 1}`}
                />
              ))}
            </div>

            <div className="flex-1 rounded-lg border bg-white p-4">
              <img
                src={activeImage}
                className="h-[400px] w-full object-contain"
                alt={product.name}
              />
            </div>
          </div>

          <div>
            <h1 className="text-xl font-semibold">{product.name}</h1>

            <div className="mt-2 flex items-center gap-3 text-sm text-gray-500">
              <StarRow />
              <span>{reviewCount} reviews</span>
              <span>Stock: {stock}</span>
            </div>

            <div className="mt-4 flex items-center gap-3">
              <span className="text-3xl font-bold text-pink-600">
                ৳{Number(finalPrice).toFixed(2)}
              </span>

              {oldPrice && (
                <span className="text-lg text-gray-400 line-through">
                  ৳{Number(oldPrice).toFixed(2)}
                </span>
              )}

              {discountText && (
                <span className="text-orange-500">{discountText}</span>
              )}
            </div>

            <div className="mt-4 flex items-center gap-3">
              <button
                onClick={() => setQty((prev) => (prev > 1 ? prev - 1 : 1))}
                className="border px-3 py-1"
                type="button"
              >
                -
              </button>
              <span>{qty}</span>
              <button
                onClick={() =>
                  setQty((prev) => {
                    if (stock > 0 && prev >= stock) return prev;
                    return prev + 1;
                  })
                }
                className="border px-3 py-1"
                type="button"
              >
                +
              </button>
            </div>

            <div className="mt-5 flex gap-3">
              <button
                className="rounded bg-orange-500 px-6 py-2 text-white disabled:opacity-60"
                type="button"
                onClick={handleBuyNow}
                disabled={adding || stock < 1}
              >
                Buy Now
              </button>
              <button
                className="rounded bg-pink-600 px-6 py-2 text-white disabled:opacity-60"
                type="button"
                onClick={handleAddToCart}
                disabled={adding || stock < 1}
              >
                {adding ? "Adding..." : "Add to Cart"}
              </button>
            </div>
          </div>
        </div>

        <div className="mt-10">
          <div className="flex gap-6 border-b pb-2">
            <button
              onClick={() => setActiveTab("description")}
              className={activeTab === "description" ? "font-bold" : ""}
              type="button"
            >
              Description
            </button>
            <button
              onClick={() => setActiveTab("reviews")}
              className={activeTab === "reviews" ? "font-bold" : ""}
              type="button"
            >
              Reviews ({reviewCount})
            </button>
          </div>

          {activeTab === "description" ? (
            <div className="mt-4">
              <div
                className={`overflow-hidden ${expanded ? "" : "max-h-40"}`}
                dangerouslySetInnerHTML={{ __html: description }}
              />

              <button
                onClick={() => setExpanded(!expanded)}
                className="mt-2 text-pink-600"
                type="button"
              >
                {expanded ? "See Less" : "See More"}
              </button>
            </div>
          ) : (
            <div className="mt-4 text-gray-500">No reviews yet.</div>
          )}
        </div>

        {similarProducts.length > 0 && (
          <div className="mt-10">
            <h2 className="mb-4 text-xl font-semibold">Similar Products</h2>

            <div className="grid grid-cols-2 gap-4 md:grid-cols-4 lg:grid-cols-6">
              {similarProducts.map((item) => (
                <SimilarProductCard key={item.id} product={item} />
              ))}
            </div>
          </div>
        )}
      </div>
    </div>
  );
}

Show.layout = (page) => <AppLayout>{page}</AppLayout>;

export default Show;