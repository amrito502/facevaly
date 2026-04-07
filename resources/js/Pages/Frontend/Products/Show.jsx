import React, { useMemo, useState } from "react";
import AppLayout from "@/Layouts/AppLayout";

/* ⭐ Rating */
function StarRow({ count = 5 }) {
  return (
    <div className="flex items-center gap-1 text-pink-500">
      {Array.from({ length: count }).map((_, i) => (
        <span key={i}>★</span>
      ))}
    </div>
  );
}

/* 🔘 Dot Indicator */
function DotIndicators({ count = 3, active = 0 }) {
  return (
    <div className="flex items-center justify-center gap-1 mb-2">
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

/* 🛍 Similar Product Card */
function SimilarProductCard({ product }) {
  const finalPrice = product.sale_price ?? product.price;

  return (
    <a
      href={`/products/${product.slug}`}
      className="overflow-hidden rounded-xl border border-pink-100 bg-white shadow-sm hover:shadow-md transition"
    >
      <div className="aspect-square bg-gray-100">
        <img
          src={product.image || "https://via.placeholder.com/400"}
          className="w-full h-full object-cover"
        />
      </div>

      <div className="p-3">
        <DotIndicators />

        <h3 className="line-clamp-2 text-sm text-gray-700">
          {product.name}
        </h3>

        <div className="mt-2 flex items-center gap-2">
          <span className="text-lg font-bold text-pink-600">
            ৳{finalPrice}
          </span>

          {product.sale_price && product.discount_percent && (
            <>
              <span className="text-sm line-through text-gray-400">
                ৳{product.price}
              </span>
              <span className="text-sm text-orange-400">
                -{product.discount_percent}%
              </span>
            </>
          )}
        </div>
      </div>
    </a>
  );
}

/* 📦 MAIN PAGE */
function Show({ product, similarProducts = [] }) {
  const images = product?.images?.length
    ? product.images
    : ["https://via.placeholder.com/800x800?text=No+Image"];

  const [activeImage, setActiveImage] = useState(images[0]);
  const [qty, setQty] = useState(1);
  const [activeTab, setActiveTab] = useState("description");
  const [expanded, setExpanded] = useState(false);

  const finalPrice = product?.sale_price ?? product?.price ?? 0;
  const oldPrice = product?.sale_price ? product?.price : null;

  const discountText = useMemo(() => {
    if (!product?.sale_price || !product?.price) return null;
    const percent = Math.round(
      ((product.price - product.sale_price) / product.price) * 100
    );
    return percent > 0 ? `${percent}% OFF` : null;
  }, [product]);

  const rating = product?.rating ?? 5;
  const reviewCount = product?.reviews_count ?? 0;
  const stock = product?.stock_qty ?? 0;

  const description =
    product?.description || "<p>No description available</p>";

  return (
    <div className="min-h-screen bg-gray-50 py-6">
      <div className="mx-auto max-w-7xl px-4">

        {/* 🔥 TOP SECTION */}
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">

          {/* IMAGE */}
          <div className="flex gap-4">
            <div className="flex flex-col gap-2">
              {images.map((img, i) => (
                <img
                  key={i}
                  src={img}
                  onClick={() => setActiveImage(img)}
                  className={`w-16 h-16 object-cover cursor-pointer border ${
                    activeImage === img
                      ? "border-pink-500"
                      : "border-gray-200"
                  }`}
                />
              ))}
            </div>

            <div className="flex-1 border rounded-lg bg-white p-4">
              <img
                src={activeImage}
                className="w-full h-[400px] object-contain"
              />
            </div>
          </div>

          {/* DETAILS */}
          <div>
            <h1 className="text-xl font-semibold">{product.name}</h1>

            <div className="mt-2 flex items-center gap-3 text-sm text-gray-500">
              <StarRow />
              <span>{reviewCount} reviews</span>
              <span>Stock: {stock}</span>
            </div>

            {/* PRICE */}
            <div className="mt-4 flex items-center gap-3">
              <span className="text-3xl font-bold text-pink-600">
                ৳{finalPrice}
              </span>

              {oldPrice && (
                <span className="text-lg line-through text-gray-400">
                  ৳{oldPrice}
                </span>
              )}

              {discountText && (
                <span className="text-orange-500">{discountText}</span>
              )}
            </div>

            {/* QTY */}
            <div className="mt-4 flex items-center gap-3">
              <button
                onClick={() => setQty(qty > 1 ? qty - 1 : 1)}
                className="px-3 py-1 border"
              >
                -
              </button>
              <span>{qty}</span>
              <button
                onClick={() => setQty(qty + 1)}
                className="px-3 py-1 border"
              >
                +
              </button>
            </div>

            {/* BUTTONS */}
            <div className="mt-5 flex gap-3">
              <button className="bg-orange-500 text-white px-6 py-2 rounded">
                Buy Now
              </button>
              <button className="bg-pink-600 text-white px-6 py-2 rounded">
                Add to Cart
              </button>
            </div>
          </div>
        </div>

        {/* 🔽 DESCRIPTION / REVIEWS */}
        <div className="mt-10">
          <div className="flex gap-6 border-b pb-2">
            <button
              onClick={() => setActiveTab("description")}
              className={activeTab === "description" ? "font-bold" : ""}
            >
              Description
            </button>
            <button
              onClick={() => setActiveTab("reviews")}
              className={activeTab === "reviews" ? "font-bold" : ""}
            >
              Reviews ({reviewCount})
            </button>
          </div>

          {activeTab === "description" ? (
            <div className="mt-4">
              <div
                className={`overflow-hidden ${
                  expanded ? "" : "max-h-40"
                }`}
                dangerouslySetInnerHTML={{ __html: description }}
              />

              <button
                onClick={() => setExpanded(!expanded)}
                className="mt-2 text-pink-600"
              >
                {expanded ? "See Less" : "See More"}
              </button>
            </div>
          ) : (
            <div className="mt-4 text-gray-500">
              No reviews yet.
            </div>
          )}
        </div>

        {/* 🔥 SIMILAR PRODUCTS */}
        {similarProducts.length > 0 && (
          <div className="mt-10">
            <h2 className="text-xl font-semibold mb-4">
              Similar Products
            </h2>

            <div className="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
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

/* Layout */
Show.layout = (page) => <AppLayout>{page}</AppLayout>;

export default Show;