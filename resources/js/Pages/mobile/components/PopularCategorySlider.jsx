import React, { useRef } from "react";


export default function PopularCategorySlider() {
    const mobile_popular_category_slider_ref = useRef(null);

    const mobile_popular_category_items = [
        {
            id: 1,
            title: "Hoses &\nPipes",
            image: "/frontend/popular_category/1.jpg",
            link: "#",
        },

         {
            id: 2,
            title: "Hoses &\nPipes",
            image: "/frontend/popular_category/2.png",
            link: "#",
        },

         {
            id: 3,
            title: "Hoses &\nPipes",
            image: "/frontend/popular_category/3.png",
            link: "#",
        },

         {
            id: 4,
            title: "Hoses &\nPipes",
            image: "/frontend/popular_category/4.jpg",
            link: "#",
        },
         {
            id: 5,
            title: "Hoses &\nPipes",
            image: "/frontend/popular_category/5.jpg",
            link: "#",
        },
         {
            id: 6,
            title: "Hoses &\nPipes",
            image: "/frontend/popular_category/6.jpg",
            link: "#",
        },
         {
            id: 7,
            title: "Hoses &\nPipes",
            image: "/frontend/popular_category/7.jpg",
            link: "#",
        },
         {
            id: 8,
            title: "Hoses &\nPipes",
            image: "/frontend/popular_category/8.jpg",
            link: "#",
        },
         {
            id: 9,
            title: "Hoses &\nPipes",
            image: "/frontend/popular_category/9.jpg",
            link: "#",
        },
         {
            id: 10,
            title: "Hoses &\nPipes",
            image: "/frontend/popular_category/10.jpg",
            link: "#",
        },
         {
            id: 11,
            title: "Hoses &\nPipes",
            image: "/frontend/popular_category/11.jpg",
            link: "#",
        },
         {
            id: 12,
            title: "Hoses &\nPipes",
            image: "/frontend/popular_category/12.jpg",
            link: "#",
        },
         {
            id: 13,
            title: "Hoses &\nPipes",
            image: "/frontend/popular_category/13.jpg",
            link: "#",
        },
         {
            id: 14,
            title: "Hoses &\nPipes",
            image: "/frontend/popular_category/14.jpg",
            link: "#",
        },
         {
            id: 15,
            title: "Hoses &\nPipes",
            image: "/frontend/popular_category/15.jpg",
            link: "#",
        },


    ];

    const mobile_popular_category_handle_wheel = (event) => {
        const slider = mobile_popular_category_slider_ref.current;
        if (!slider) return;

        if (Math.abs(event.deltaY) > Math.abs(event.deltaX)) {
            slider.scrollLeft += event.deltaY;
        }
    };

    return (
        <div id="mobile_popular_category_section">
            <div className="mobile_popular_category_header">
                <h2 className="mobile_popular_category_title">
                    Popular Categories For You
                </h2>

                <a href="#" className="mobile_popular_category_more_link">
                    Scroll More
                    <span className="mobile_popular_category_more_arrow">›</span>
                </a>
            </div>

            <div
                className="mobile_popular_category_slider"
                ref={mobile_popular_category_slider_ref}
                onWheel={mobile_popular_category_handle_wheel}
            >
                {mobile_popular_category_items.map((item) => (
                    <a
                        href={item.link}
                        key={item.id}
                        className="mobile_popular_category_item"
                    >
                        <div className="mobile_popular_category_image_box">
                            <img
                                src={item.image}
                                alt={item.title.replace("\n", " ")}
                                className="mobile_popular_category_image"
                            />
                        </div>

                        <p className="mobile_popular_category_text">
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
    );
}
