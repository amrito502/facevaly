import React from 'react'
import SellingStepsFlow from '../Components/SellingStepsFlow'
import FeatureCardsSection from '../Components/FeatureCardsSection'
import SellerHeroSection from '../Components/SellerHeroSection'
import FacevalyStepsSection from '../Components/FacevalyStepsSection'
import FacevalyHeroBanner from '../Components/FacevalyHeroBanner'
import FacevalyCtaBanner from '../Components/FacevalyCtaBanner'
import FacevalyTopBar from '../Components/FacevalyTopBar'
import FacevalyFaqSection from '../Components/FacevalyFaqSection'

export default function Index() {
  return (
    <div>
      <FacevalyTopBar />
      <FacevalyHeroBanner />
        
      <FeatureCardsSection />
         <FacevalyStepsSection />
       <SellingStepsFlow />
       <SellerHeroSection />
  
       <FacevalyFaqSection />
       <FacevalyCtaBanner />
    </div>
  )
}

