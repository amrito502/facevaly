import React, { useState } from "react";

const flashProducts = [
  {
    id: 1,
    name: "Health Paradise Organic Baby Noodle",
    image: "/frontend/flashsale/1.avif",
    price: 800,
    discount: 0,
    rating: 0,
    reviews: 0,
  },
  {
    id: 2,
    name: "Skin'O Strawberry Scented Shower Gel",
    image: "/frontend/flashsale/2.avif",
    price: 250,
    discount: 20,
    rating: 0,
    reviews: 0,
  },
  {
    id: 3,
    name: "TP-Link Archer AX15 AX1500 Wi-Fi 6 Router",
    image: "/frontend/flashsale/3.avif",
    price: 3990,
    discount: 20,
    rating: 0,
    reviews: 0,
  },
  {
    id: 4,
    name: "Lotto Durable Casual Shoe for Men",
    image: "/frontend/flashsale/3.avif",
    price: 644,
    discount: 50,
    rating: 0,
    reviews: 0,
  },
  {
    id: 5,
    name: "Starship Full Cream Milk Powder 500gm",
    image: "/frontend/flashsale/1.avif",
    price: 361,
    discount: 2,
    rating: 0,
    reviews: 0,
  },
  {
    id: 6,
    name: "Premium Casual Sports Shoe for Men",
    image: "/frontend/flashsale/2.avif",
    price: 1120,
    discount: 15,
    rating: 0,
    reviews: 0,
  },
  {
    id: 7,
    name: "Wireless Neckband Earphone",
    image: "/frontend/flashsale/3.avif",
    price: 590,
    discount: 25,
    rating: 0,
    reviews: 0,
  },
];

function ProductCard({ product }) {
  return (
    <div className="bg-[#f5f5f5] border-r border-b border-[#e7e7e7]">
      <div className="aspect-[1/1] w-full overflow-hidden bg-white ">
        <img
          src={product.image}
          alt={product.name}
          className="h-full w-full object-cover"
        />
      </div>

      <div className="px-3 pt-2 pb-3">
        <div className="mb-2 flex items-center gap-1 text-[13px] text-[#9e9e9e]">
          <span className="text-[#f5a623]">★</span>
          <span>{product.rating}</span>
          <span>({product.reviews})</span>
        </div>

        <h3 className="min-h-[52px] text-[15px] leading-[1.4] text-[#212121] line-clamp-2">
          {product.name}
        </h3>
      </div>

      <div className="flex items-center justify-between bg-[#eaf3f2] px-3 py-2">
        <span className="text-[15px] font-semibold text-[#f57224]">
          ৳{product.price}
        </span>

        <div className="relative bg-[#12b8a6] px-3 py-1 text-[13px] font-semibold text-white">
          -{product.discount}%
          <span className="absolute -left-[10px] top-0 h-0 w-0 border-y-[16px] border-r-[10px] border-y-transparent border-r-[#12b8a6]" />
        </div>
      </div>
    </div>
  );
}

export default function FlashSale() {
  const visibleCards = 5;
  const maxIndex = Math.max(flashProducts.length - visibleCards, 0);
  const [currentIndex, setCurrentIndex] = useState(0);

  const nextSlide = () => {
    setCurrentIndex((prev) => (prev >= maxIndex ? 0 : prev + 1));
  };

  const prevSlide = () => {
    setCurrentIndex((prev) => (prev <= 0 ? maxIndex : prev - 1));
  };

  return (
    <section className="mt-6 w-full bg-[#f5f5f5] py-4">
      <div className="mx-auto w-full max-w-[1188px]">
        <div className="mb-4 flex items-end justify-between">
          <div>
            <h2 className="text-[22px] font-semibold text-[#212121]">
              Flash Sale
            </h2>
            <p className="mt-1 text-[15px] text-[#f57224]">On Sale Now</p>
          </div>

          <button className="border border-[#f57224] px-5 py-2 text-[14px] font-medium text-[#f57224] transition hover:bg-[#f57224] hover:text-white">
            SHOP ALL PRODUCTS
          </button>
        </div>

        <div className="relative">
          <button
            onClick={prevSlide}
            className="absolute -left-5 top-1/2 z-20 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full bg-white text-[#666] border border-[#e5e5e5] hover:text-[#f57224]"
            aria-label="Previous"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              strokeWidth="2.2"
              className="h-4 w-4"
            >
              <path
                strokeLinecap="round"
                strokeLinejoin="round"
                d="M15 18l-6-6 6-6"
              />
            </svg>
          </button>

          <div className="overflow-hidden">
            <div
              className="flex transition-transform duration-500 ease-in-out"
              style={{
                transform: `translateX(-${currentIndex * (100 / visibleCards)}%)`,
              }}
            >
              {flashProducts.map((product) => (
                <div
                  key={product.id}
                  className="w-1/5 flex-shrink-0 px-[8px]"
                >
                  <ProductCard product={product} />
                </div>
              ))}
            </div>
          </div>

          <button
            onClick={nextSlide}
            className="absolute -right-5 top-1/2 z-20 flex h-10 w-10 -translate-y-1/2 items-center justify-center rounded-full bg-white text-[#666] border border-[#e5e5e5] hover:text-[#f57224]"
            aria-label="Next"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              strokeWidth="2.2"
              className="h-4 w-4"
            >
              <path
                strokeLinecap="round"
                strokeLinejoin="round"
                d="M9 6l6 6-6 6"
              />
            </svg>
          </button>
        </div>
      </div>
    </section>
  );
}