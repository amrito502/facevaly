import React from "react";

export default function FlashSaleSection() {
    const mobile_flash_sale_products = [
        {
            id: 1,
            name: "Biyam Tropical Kernel",
            image: "/frontend/flashsale/1.avif",
            discount: "SAVE 43%",
            price: 273,
            oldPrice: 480,
            stockText: "6 Stock left",
            link: "#",
            badgeText: "Premium Quality Special",
        },
        {
            id: 2,
            name: "Mixed Nuts Jar",
            image: "/frontend/flashsale/2.avif",
            discount: "SAVE 68%",
            price: 161,
            oldPrice: 500,
            stockText: "8 Stock left",
            link: "#",
            badgeText: "",
        },
        {
            id: 3,
            name: "Grey Storage Basket",
            image: "/frontend/flashsale/3.avif",
            discount: "SAVE 65%",
            price: 98,
            oldPrice: 280,
            stockText: "7 Stock left",
            link: "#",
            badgeText: "",
        },
    ];

    return (
        <div id="mobile_flash_sale_section">
            <div className="mobile_flash_sale_container">
                <div className="mobile_flash_sale_header">
                    <h2 className="mobile_flash_sale_title">
                        Fla<span className="mobile_flash_sale_title_icon">⚡</span>h Sale
                    </h2>

                    <a href="#" className="mobile_flash_sale_more_link">
                        Shop More
                        <span className="mobile_flash_sale_more_arrow">›</span>
                    </a>
                </div>

                <div className="mobile_flash_sale_products">
                    {mobile_flash_sale_products.map((product, index) => (
                        <a
                            href={product.link}
                            key={product.id}
                            className="mobile_flash_sale_card"
                        >
                            <div className="mobile_flash_sale_image_wrap">
                                <span className="mobile_flash_sale_discount_badge">
                                    {product.discount}
                                </span>

                                {index === 0 && product.badgeText ? (
                                    <span className="mobile_flash_sale_vertical_badge">
                                        {product.badgeText}
                                    </span>
                                ) : null}

                                <img
                                    src={product.image}
                                    alt={product.name}
                                    className="mobile_flash_sale_image"
                                />
                            </div>

                            <div className="mobile_flash_sale_info">
                                <div className="mobile_flash_sale_price_row">
                                    <span className="mobile_flash_sale_currency">৳</span>
                                    <span className="mobile_flash_sale_price">
                                        {product.price}
                                    </span>
                                </div>

                                <div className="mobile_flash_sale_old_price_row">
                                    <span className="mobile_flash_sale_old_currency">৳</span>
                                    <span className="mobile_flash_sale_old_price">
                                        {product.oldPrice}
                                    </span>
                                </div>

                                <div className="mobile_flash_sale_stock_box">
                                    <span className="mobile_flash_sale_stock_icon">🔥</span>
                                    <span className="mobile_flash_sale_stock_text">
                                        {product.stockText}
                                    </span>
                                </div>
                            </div>
                        </a>
                    ))}
                </div>
            </div>
        </div>
    );
}
