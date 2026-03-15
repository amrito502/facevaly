import React, { useEffect, useState } from "react";

export default function Banner() {
    const mobile_banner_slides = [
        {
            id: 1,
            image: "/frontend/banner/1.avif",
            link: "#",
        },
        {
            id: 2,
            image: "/frontend/banner/2.avif",
            link: "#",
        },
        {
            id: 3,
            image: "/frontend/banner/3.avif",
            link: "#",
        },
        {
            id: 4,
            image: "/frontend/banner/4.avif",
            link: "#",
        },
         {
            id: 5,
            image: "/frontend/banner/5.avif",
            link: "#",
        },
    ];

    const [mobile_banner_active_index, setMobileBannerActiveIndex] = useState(0);

    useEffect(() => {
        const mobile_banner_interval = setInterval(() => {
            setMobileBannerActiveIndex((prevIndex) =>
                prevIndex === mobile_banner_slides.length - 1 ? 0 : prevIndex + 1
            );
        }, 3000);

        return () => clearInterval(mobile_banner_interval);
    }, [mobile_banner_slides.length]);

    const mobile_banner_go_to_slide = (index) => {
        setMobileBannerActiveIndex(index);
    };

    return (
        <div id="mobile_banner_section">
            <div className="mobile_banner_slider_wrapper">
                <div
                    className="mobile_banner_slider_track"
                    style={{
                        transform: `translateX(-${mobile_banner_active_index * 100}%)`,
                    }}
                >
                    {mobile_banner_slides.map((slide) => (
                        <a
                            key={slide.id}
                            href={slide.link}
                            className="mobile_banner_slide_item"
                        >
                            <img
                                src={slide.image}
                                alt={`mobile-banner-${slide.id}`}
                                className="mobile_banner_slide_image"
                            />
                        </a>
                    ))}
                </div>

                <div className="mobile_banner_dots_wrapper">
                    {mobile_banner_slides.map((_, index) => (
                        <button
                            key={index}
                            type="button"
                            className={`mobile_banner_dot ${
                                mobile_banner_active_index === index
                                    ? "mobile_banner_dot_active"
                                    : ""
                            }`}
                            onClick={() => mobile_banner_go_to_slide(index)}
                        />
                    ))}
                </div>
            </div>
        </div>
    );
}
