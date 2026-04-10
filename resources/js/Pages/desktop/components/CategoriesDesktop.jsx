import React from "react";

const categories = [
  {
    id: 1,
    name: "Other Projector Accessories",
    image: "/frontend/popular_category/1.jpg",
  },
  {
    id: 2,
    name: "Kitchen Fittings",
    image: "/frontend/popular_category/1.jpg",
  },
  {
    id: 3,
    name: "Womens Fashion",
    image: "/frontend/popular_category/1.jpg",
  },
  {
    id: 4,
    name: "Watches and Accessories",
    image: "/frontend/popular_category/1.jpg",
  },
  {
    id: 5,
    name: "Goat",
    image: "/frontend/popular_category/1.jpg",
  },
  {
    id: 6,
    name: "Watering Systems & Garden Hoses",
    image: "/frontend/popular_category/1.jpg",
  },
  {
    id: 7,
    name: "Margarine & Spread",
    image: "/frontend/popular_category/1.jpg",
  },
  {
    id: 8,
    name: "Bedding Sets",
    image: "/frontend/popular_category/1.jpg",
  },
  {
    id: 9,
    name: "Pools",
    image: "/frontend/popular_category/1.jpg",
  },
  {
    id: 10,
    name: "Pants, Palazzos & Capris",
    image: "/frontend/popular_category/1.jpg",
  },
  {
    id: 11,
    name: "Digital Downloads",
    image: "/frontend/popular_category/1.jpg",
  },
  {
    id: 12,
    name: "Beans & Chickpeas",
    image: "/frontend/popular_category/1.jpg",
  },
  {
    id: 13,
    name: "Wire Racks",
    image: "/frontend/popular_category/1.jpg",
  },
  {
    id: 14,
    name: "Magnet & Felt Playboards",
    image: "/frontend/popular_category/1.jpg",
  },
  {
    id: 15,
    name: "Pendants",
    image: "/frontend/popular_category/1.jpg",
  },
  {
    id: 16,
    name: "Others",
    image: "/frontend/popular_category/1.jpg",
  },
];

function CategoryCard({ item }) {
  return (
    <div className="group flex h-[148px] cursor-pointer flex-col items-center justify-start border-r border-b border-[#e7e7e7] bg-white px-3 pt-4 text-center transition hover:shadow-none">
      <div className="flex h-[80px] w-[80px] items-center justify-center overflow-hidden bg-white">
        <img
          src={item.image}
          alt={item.name}
          className="max-h-full max-w-full object-contain transition duration-200 group-hover:scale-[1.03]"
        />
      </div>

      <h3 className="mt-3 line-clamp-2 text-[14px] font-normal leading-[1.3] text-[#212121]">
        {item.name}
      </h3>
    </div>
  );
}

export default function CategoriesDesktop() {
  return (
    <section className="mt-6 hidden w-full lg:block">
      <div className="mx-auto w-full max-w-[1188px]">
        <div className="mb-3">
          <h2 className="text-[28px] font-normal leading-none text-[#212121]">
            Categories
          </h2>
        </div>

        <div className="grid grid-cols-8 border-l border-t border-[#e7e7e7] bg-white">
          {categories.map((item) => (
            <CategoryCard key={item.id} item={item} />
          ))}
        </div>
      </div>
    </section>
  );
}