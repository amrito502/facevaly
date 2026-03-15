import React from "react";
import AppLayout from "@/Layouts/AppLayout";
import { router } from '@inertiajs/react'
import Banner from "./mobile/components/Banner";
import Channel from "./mobile/components/Channel";
import PromotionBanner from "./mobile/components/PromotionBanner";
import FlashSaleSection from "./mobile/components/FlashSaleSection";
import PopularCategorySlider from "./mobile/components/PopularCategorySlider";
import CategoryProductFeed from "./mobile/components/CategoryProductFeed";

export default function Home() {
  return (
    <div>
      <Banner />
      <Channel />
      <PromotionBanner />
      <FlashSaleSection />
      <PopularCategorySlider />
      <CategoryProductFeed />
    </div>
  );
}

Home.layout = (page) => <AppLayout>{page}</AppLayout>;
