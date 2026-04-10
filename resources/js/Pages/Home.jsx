import React from "react";
import AppLayout from "@/Layouts/AppLayout";
import { router } from '@inertiajs/react'
import Banner from "./mobile/components/Banner";
import Channel from "./mobile/components/Channel";
import PromotionBanner from "./mobile/components/PromotionBanner";
import FlashSaleSection from "./mobile/components/FlashSaleSection";
import PopularCategorySlider from "./mobile/components/PopularCategorySlider";
import CategoryProductFeed from "./mobile/components/CategoryProductFeed";
import HeroSliderDesktop from "./desktop/components/HeroSliderDesktop";
import PromotionBannerDesktop from "./desktop/components/PromotionBannerDesktop";
import FlashSale from "./desktop/components/FlashSale";
import CategoriesDesktop from "./desktop/components/CategoriesDesktop";
import ShopReelDesktop from "./desktop/components/ShopReelDesktop";

export default function Home() {
  return (
    <div>
      {/* ====start-banner-section======= */}
      <HeroSliderDesktop />
      <Banner />
       {/* ====end-banner-section======= */}

      {/* ====promotions banner-section======= */}
      <PromotionBannerDesktop />
      {/* ====promotions banner-section======= */}

      <FlashSale />
      <CategoriesDesktop />
      <ShopReelDesktop />

      <Channel />
      <PromotionBanner />
      <FlashSaleSection />
      <PopularCategorySlider />
      <CategoryProductFeed />
    </div>
  );
}

Home.layout = (page) => <AppLayout>{page}</AppLayout>;
