import './bootstrap'
import '../css/app.css'
import '../css/layout.css'
import '../css/MobileBannerSlider.css'
import '../css/MobileCategorySlider.css'
import '../css/MobilePromotionBanner.css'
import '../css/MobileFlashSaleSection.css'
import '../css/MobilePopularCategorySlider.css'
import '../css/MobileCategoryProductFeed.css'



import React from 'react'
import { createRoot } from 'react-dom/client'
import { createInertiaApp } from '@inertiajs/react'

createInertiaApp({
  resolve: (name) => {
    const pages = import.meta.glob('./Pages/**/*.jsx', { eager: true })
    return pages[`./Pages/${name}.jsx`]
  },
  setup({ el, App, props }) {
    createRoot(el).render(<App {...props} />)
  },
})
