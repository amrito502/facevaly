import { useEffect, useState } from "react";

export default function HeroSliderDesktop() {
    const slides = [
        {
            id: 1,
            image: "/frontend/banner/1.avif",
            alt: "Desktop Slide 1",
        },
        {
            id: 2,
            image: "/frontend/banner/2.avif",
            alt: "Desktop Slide 2",
        },
        {
            id: 3,
            image: "/frontend/banner/3.avif",
            alt: "Desktop Slide 3",
        },
    ];

    const [currentIndex, setCurrentIndex] = useState(0);

    const nextSlide = () => {
        setCurrentIndex((prev) => (prev + 1) % slides.length);
    };

    const prevSlide = () => {
        setCurrentIndex((prev) => (prev - 1 + slides.length) % slides.length);
    };

    const goToSlide = (index) => {
        setCurrentIndex(index);
    };

    useEffect(() => {
        const interval = setInterval(() => {
            setCurrentIndex((prev) => (prev + 1) % slides.length);
        }, 4000);

        return () => clearInterval(interval);
    }, [slides.length]);

    return (
        <>
            <section className="hidden lg:block w-full bg-[#f5f5f5]"  style={{ marginTop: "-16px" }}>
                <div className="mx-auto w-full max-w-[1188px]">
                    <div className="flex h-[344px] w-full">
                        {/* Left Slider */}
                        <div className="relative w-[988px] overflow-hidden">
                            <div className="relative h-full w-full">
                                {slides.map((slide, index) => (
                                    <div
                                        key={slide.id}
                                        className={`absolute inset-0 transition-opacity duration-700 ease-in-out ${
                                            index === currentIndex
                                                ? "z-10 opacity-100"
                                                : "z-0 opacity-0"
                                        }`}
                                    >
                                        <img
                                            src={slide.image}
                                            alt={slide.alt}
                                            className="block h-full w-full object-cover"
                                        />
                                    </div>
                                ))}

                                {/* Left Arrow */}
                                <button
                                    type="button"
                                    onClick={prevSlide}
                                    className="absolute left-3 top-1/2 z-20 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full bg-[rgba(0,0,0,.15)] text-white transition hover:bg-[rgba(0,0,0,.3)]"
                                    aria-label="Previous slide"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        strokeWidth="2.5"
                                        className="h-4 w-4"
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            d="M15 18l-6-6 6-6"
                                        />
                                    </svg>
                                </button>

                                {/* Right Arrow */}
                                <button
                                    type="button"
                                    onClick={nextSlide}
                                    className="absolute right-3 top-1/2 z-20 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full bg-[rgba(0,0,0,.15)] text-white transition hover:bg-[rgba(0,0,0,.3)]"
                                    aria-label="Next slide"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        strokeWidth="2.5"
                                        className="h-4 w-4"
                                    >
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            d="M9 6l6 6-6 6"
                                        />
                                    </svg>
                                </button>

                                {/* Dots */}
                                <div className="absolute bottom-5 left-1/2 z-20 flex -translate-x-1/2 items-center gap-2">
                                    {slides.map((slide, index) => (
                                        <button
                                            key={slide.id}
                                            type="button"
                                            onClick={() => goToSlide(index)}
                                            aria-label={`Go to slide ${index + 1}`}
                                            className={`h-2.5 w-2.5 rounded-full transition ${
                                                index === currentIndex
                                                    ? "bg-white"
                                                    : "bg-white/50"
                                            }`}
                                        />
                                    ))}
                                </div>
                            </div>
                        </div>

                        {/* Right App Download */}
                        <div className="w-[200px] bg-[#f5ebee] px-2 pt-2 pb-3">
                            <div className="mb-2 flex items-center gap-2 px-1">
                                <div className="flex h-7 w-7 items-center justify-center overflow-hidden">
                                    <img
                                        src="/frontend/logo.png"
                                        alt="App Icon"
                                        className="h-full w-full object-contain"
                                    />
                                </div>
                                <h5 className="text-[13px] font-semibold text-[#222]">
                                    Download the App
                                </h5>
                            </div>

                            <div className="mb-1 w-fit rounded-t-md bg-[#ff9f2f] px-2 py-[3px] text-[11px] font-bold leading-none text-white">
                                4.8 Rated
                            </div>

                            <div className="rounded-r-md rounded-bl-md bg-gradient-to-b from-[#ff8a23] to-[#f54fa5] px-3 py-3 text-white shadow-sm" style={{
  background: "linear-gradient(180deg, #FF7A00 0%, #FF5E62 50%, #E84393 100%)"
}}>
                                <h6 className="mb-4 text-center text-[15px] font-bold">
                                    Download App
                                </h6>

                                <div className="mb-3 flex items-center gap-2.5">
                                    <div className="flex h-[34px] w-[34px] min-w-[34px] items-center justify-center rounded-[10px] bg-white">
                                        <img
                                            src="/images/3.avif"
                                            alt="Free Delivery"
                                            className="h-[18px] w-[18px] object-contain"
                                        />
                                    </div>
                                    <div className="text-[13px] font-bold leading-[1.05]">
                                        <div>Free</div>
                                        <div>Delivery</div>
                                    </div>
                                </div>

                                <div className="flex items-center gap-2.5">
                                    <div className="flex h-[34px] w-[34px] min-w-[34px] items-center justify-center rounded-[10px] bg-white">
                                        <img
                                            src="/images/2.avif"
                                            alt="Limited Time"
                                            className="h-[18px] w-[18px] object-contain"
                                        />
                                    </div>
                                    <div className="text-[13px] font-bold leading-[1.05]">
                                        <div>Limited</div>
                                        <div>Time</div>
                                    </div>
                                </div>
                            </div>

                            <div className="mt-3 flex items-start gap-2">
                                <div className="h-[84px] w-[84px] shrink-0 overflow-hidden rounded bg-white p-1">
                                    <img
                                        src="/images/1.avif"
                                        alt="QR Code"
                                        className="h-full w-full object-cover"
                                    />
                                </div>

                                <div className="flex flex-1 flex-col gap-2">
                                    <a href="#" className="block">
                                        <img
                                            src="/images/app-store.png"
                                            alt="App Store"
                                            className="block h-[28px] w-auto"
                                        />
                                    </a>

                                    <a href="#" className="block">
                                        <img
                                            src="/images/4.svg"
                                            alt="Google Play"
                                            className="block h-[28px] w-auto"
                                        />
                                    </a>
                                </div>
                            </div>

                            <p className="mt-2 text-[12px] font-medium text-[#111]">
                                Download the App Now!
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {/* Mobile e kichui show hobe na */}
            <style jsx>{`
                @media (max-width: 1023px) {
                    section {
                        display: none !important;
                    }
                }
            `}</style>
        </>
    );
}