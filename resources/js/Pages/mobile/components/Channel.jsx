import React, { useMemo, useState } from "react";


export default function Channel() {
    const mobile_category_items = [
        {
            id: 1,
            title: "Earn\nCoins",
            image: "/frontend/channel/1.png",
            link: "#",
        },
        {
            id: 2,
            title: "Free\nDelivery",
            image: "/frontend/channel/2.png",
            link: "#",
        },
        {
            id: 3,
            title: "Daraz\nFreebie",
            image: "/frontend/channel/3.png",
            link: "#",
        },
        {
            id: 4,
            title: "Beauty",
            image: "/frontend/channel/4.png",
            link: "#",
        },
        {
            id: 5,
            title: "Buy More\nSave More",
            image: "/frontend/channel/5.png",
            link: "#",
        },
        {
            id: 6,
            title: "Affiliate\nProgram",
            image: "/frontend/channel/6.png",
            link: "#",
        },
        {
            id: 7,
            title: "Eid\nSale",
            image: "/frontend/channel/7.png",
            link: "#",
        },
        {
            id: 8,
            title: "Buy\nAny 3",
            image: "/frontend/channel/8.png",
            link: "#",
        },
        {
            id: 9,
            title: "DarazMall",
            image: "/frontend/channel/9.png",
            link: "#",
        },
        {
            id: 10,
            title: "New\nArrivals",
            image: "/frontend/channel/10.png",
            link: "#",
        },
        {
            id: 11,
            title: "DarazLook",
            image: "/frontend/channel/11.png",
            link: "#",
        },
        {
            id: 12,
            title: "Play & Win",
            image: "/frontend/channel/12.png",
            link: "#",
        },
         {
            id: 13,
            title: "Earn\nCoins",
            image: "/frontend/channel/1.png",
            link: "#",
        },
        {
            id: 14,
            title: "Free\nDelivery",
            image: "/frontend/channel/2.png",
            link: "#",
        },
        {
            id: 15,
            title: "Daraz\nFreebie",
            image: "/frontend/channel/3.png",
            link: "#",
        },
        {
            id: 16,
            title: "Beauty",
            image: "/frontend/channel/4.png",
            link: "#",
        },
        {
            id: 17,
            title: "Buy More\nSave More",
            image: "/frontend/channel/5.png",
            link: "#",
        },
        {
            id: 18,
            title: "Affiliate\nProgram",
            image: "/frontend/channel/6.png",
            link: "#",
        },
        {
            id: 19,
            title: "Eid\nSale",
            image: "/frontend/channel/7.png",
            link: "#",
        },
        {
            id: 20,
            title: "Buy\nAny 3",
            image: "/frontend/channel/8.png",
            link: "#",
        },
      
    ];

    const mobile_category_items_per_page = 10;

    const mobile_category_pages = useMemo(() => {
        const mobile_category_chunked = [];
        for (let i = 0; i < mobile_category_items.length; i += mobile_category_items_per_page) {
            mobile_category_chunked.push(
                mobile_category_items.slice(i, i + mobile_category_items_per_page)
            );
        }
        return mobile_category_chunked;
    }, []);

    const [mobile_category_active_page, setMobileCategoryActivePage] = useState(0);
    const [mobile_category_touch_start_x, setMobileCategoryTouchStartX] = useState(0);
    const [mobile_category_touch_end_x, setMobileCategoryTouchEndX] = useState(0);

    const mobile_category_handle_touch_start = (event) => {
        setMobileCategoryTouchStartX(event.targetTouches[0].clientX);
    };

    const mobile_category_handle_touch_move = (event) => {
        setMobileCategoryTouchEndX(event.targetTouches[0].clientX);
    };

    const mobile_category_handle_touch_end = () => {
        const mobile_category_swipe_distance =
            mobile_category_touch_start_x - mobile_category_touch_end_x;

        if (mobile_category_swipe_distance > 50) {
            setMobileCategoryActivePage((prev) =>
                prev < mobile_category_pages.length - 1 ? prev + 1 : prev
            );
        }

        if (mobile_category_swipe_distance < -50) {
            setMobileCategoryActivePage((prev) => (prev > 0 ? prev - 1 : prev));
        }
    };

    const mobile_category_go_to_page = (pageIndex) => {
        setMobileCategoryActivePage(pageIndex);
    };

    return (
        <div id="mobile_category_section">
            <div
                className="mobile_category_slider_outer"
                onTouchStart={mobile_category_handle_touch_start}
                onTouchMove={mobile_category_handle_touch_move}
                onTouchEnd={mobile_category_handle_touch_end}
            >
                <div
                    className="mobile_category_slider_track"
                    style={{
                        transform: `translateX(-${mobile_category_active_page * 100}%)`,
                    }}
                >
                    {mobile_category_pages.map((pageItems, pageIndex) => (
                        <div className="mobile_category_page" key={pageIndex}>
                            <div className="mobile_category_grid">
                                {pageItems.map((item) => (
                                    <a
                                        key={item.id}
                                        href={item.link}
                                        className="mobile_category_item"
                                    >
                                        <div className="mobile_category_icon_wrap">
                                            <img
                                                src={item.image}
                                                alt={item.title.replace("\n", " ")}
                                                className="mobile_category_icon"
                                            />
                                        </div>

                                        <p className="mobile_category_text">
                                            {item.title.split("\n").map((line, index, array) => (
                                                <React.Fragment key={index}>
                                                    {line}
                                                    {index !== array.length - 1 && <br />}
                                                </React.Fragment>
                                            ))}
                                        </p>
                                    </a>
                                ))}
                            </div>
                        </div>
                    ))}
                </div>
            </div>

            <div className="mobile_category_dots">
                {mobile_category_pages.map((_, index) => (
                    <button
                        key={index}
                        type="button"
                        className={`mobile_category_dot ${
                            mobile_category_active_page === index
                                ? "mobile_category_dot_active"
                                : ""
                        }`}
                        onClick={() => mobile_category_go_to_page(index)}
                    />
                ))}
            </div>
        </div>
    );
}
