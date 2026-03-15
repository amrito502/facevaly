import React, { useEffect, useMemo, useRef, useState } from "react";


export default function CategoryProductFeed() {
    const mobile_feed_categories = [
        {
            id: "all",
            name: "All",
            icon: "https://cdn-icons-png.flaticon.com/128/3081/3081559.png",
        },
        {
            id: "hot",
            name: "Hot Deals",
            icon: "https://cdn-icons-png.flaticon.com/128/1828/1828884.png",
        },
        {
            id: "voucher",
            name: "Voucher",
            icon: "https://cdn-icons-png.flaticon.com/128/2331/2331966.png",
        },
        {
            id: "look",
            name: "Daraz Look",
            icon: "https://cdn-icons-png.flaticon.com/128/3050/3050525.png",
        },
        {
            id: "free",
            name: "Free",
            icon: "https://cdn-icons-png.flaticon.com/128/891/891462.png",
        },
        {
            id: "beauty",
            name: "Beauty",
            icon: "https://cdn-icons-png.flaticon.com/128/3163/3163209.png",
        },
    ];

    const mobile_feed_product_titles = {
        hot: [
            "Wireless Bluetooth Earbuds",
            "Fast Charging Power Bank",
            "Gaming Mouse RGB Edition",
            "Portable Mini Speaker",
            "Smart LED Desk Lamp",
            "Foldable Phone Holder",
            "Metal Router Wall Stand",
            "USB Type C Cable Pack",
            "Noise Reduction Headphone",
            "Laptop Cooling Pad Pro",
            "Electric Kettle Premium",
            "Travel Backpack Waterproof",
            "Digital Alarm Clock LED",
            "Non Stick Fry Pan Set",
            "Kitchen Knife Sharp Set",
            "Car Vacuum Cleaner Mini",
            "Baby Feeding Bottle Set",
            "Home Storage Organizer",
            "Bluetooth Calling Smart Watch",
            "Rechargeable Table Fan",
        ],
        voucher: [
            "Luxury Glass Water Bottle",
            "Portable Juice Blender",
            "Touch Sensor Night Lamp",
            "Silicone Lunch Box Set",
            "Soft Cotton Bath Towel",
            "Classic Wall Clock Round",
            "Magnetic Spice Jar Rack",
            "Vacuum Flask Stainless Steel",
            "Premium Dry Food Container",
            "Kids Learning Drawing Board",
            "Compact Travel Iron",
            "Auto Soap Dispenser",
            "Ceramic Coffee Mug Set",
            "Shoe Storage Bag Large",
            "Mini Hair Dryer Foldable",
            "Reusable Grocery Bag",
            "Laptop Sleeve Protective",
            "Rechargeable Nail Trimmer",
            "Microwave Cover Protector",
            "Smart Mini Calculator",
        ],
        look: [
            "Luxury Fashion Watch For Women",
            "Stylish Ladies Handbag",
            "Classic Men Wallet Leather",
            "Premium Sunglass UV Protection",
            "Elegant Bracelet Jewelry",
            "Fashion Shoulder Bag Trendy",
            "Casual Sneakers For Men",
            "Soft Leather Belt Premium",
            "Ladies Party Clutch Bag",
            "Sports Analog Wrist Watch",
            "Canvas Fashion Backpack",
            "Trendy Hair Band Set",
            "Stylish Ring Collection",
            "Elegant Scarf For Women",
            "Comfort Slipper Home Wear",
            "Fashionable Crossbody Bag",
            "Luxury Couple Watch Set",
            "Premium Cap Adjustable",
            "Party Heel Sandal Women",
            "Smart Casual Polo T Shirt",
        ],
        free: [
            "Kitchen Storage Box",
            "Multipurpose Wall Hook Pack",
            "Plastic Food Container Set",
            "Bathroom Shelf Rack",
            "Cleaning Brush 3 In 1",
            "Laundry Basket Foldable",
            "Silicone Toilet Brush",
            "Hanging Closet Organizer",
            "Desk Cable Organizer",
            "Mini Trash Bin Desktop",
            "Portable Tissue Box Holder",
            "Soap Tray Drain Rack",
            "Fridge Storage Drawer",
            "Microwave Safe Bowl Set",
            "Sponge Holder Kitchen Sink",
            "Water Bottle Cleaning Brush",
            "Wardrobe Perfume Sachet",
            "Rice Washing Bowl",
            "Drawer Partition Organizer",
            "Cup Drying Rack Holder",
        ],
        beauty: [
            "18 Colors Eyeshadow Palette",
            "Matte Lipstick Waterproof",
            "Professional Makeup Brush Set",
            "Facial Cleanser Gentle Wash",
            "Vitamin C Serum Brightening",
            "Compact Face Powder",
            "Waterproof Eyeliner Pen",
            "Soft Beauty Blender Sponge",
            "Hair Straightener Brush",
            "Nail Polish Combo Pack",
            "Aloe Vera Gel Skin Care",
            "Perfume Long Lasting",
            "Lip Gloss Shiny Finish",
            "Face Mist Hydrating Spray",
            "Makeup Organizer Box",
            "BB Cream Natural Glow",
            "Hair Serum Smooth Finish",
            "Facial Sheet Mask Combo",
            "Compact Travel Makeup Kit",
            "Rose Water Toner Fresh",
        ],
    };

    const mobile_feed_product_images = [
        "https://images.unsplash.com/photo-1512496015851-a90fb38ba796?auto=format&fit=crop&w=800&q=80",
        "https://images.unsplash.com/photo-1523170335258-f5ed11844a49?auto=format&fit=crop&w=800&q=80",
        "https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=800&q=80",
        "https://images.unsplash.com/photo-1558002038-1055907df827?auto=format&fit=crop&w=800&q=80",
        "https://images.unsplash.com/photo-1631214540242-6d6b0dba1f7d?auto=format&fit=crop&w=800&q=80",
        "https://images.unsplash.com/photo-1570222094114-d054a817e56b?auto=format&fit=crop&w=800&q=80",
        "https://images.unsplash.com/photo-1584269600519-112d071b65ca?auto=format&fit=crop&w=800&q=80",
        "https://images.unsplash.com/photo-1584917865442-de89df76afd3?auto=format&fit=crop&w=800&q=80",
        "https://images.unsplash.com/photo-1606220945770-b5b6c2c55bf1?auto=format&fit=crop&w=800&q=80",
        "https://images.unsplash.com/photo-1507473885765-e6ed057f782c?auto=format&fit=crop&w=800&q=80",
        "https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&w=800&q=80",
        "https://images.unsplash.com/photo-1616627459910-1d77f8f807d5?auto=format&fit=crop&w=800&q=80",
        "https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=800&q=80",
        "https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=800&q=80",
        "https://images.unsplash.com/photo-1546868871-7041f2a55e12?auto=format&fit=crop&w=800&q=80",
        "https://images.unsplash.com/photo-1585386959984-a4155224a1ad?auto=format&fit=crop&w=800&q=80",
        "https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=800&q=80",
        "https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=800&q=80",
        "https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=800&q=80",
        "https://images.unsplash.com/photo-1511556820780-d912e42b4980?auto=format&fit=crop&w=800&q=80",
        "https://images.unsplash.com/photo-1511499767150-a48a237f0083?auto=format&fit=crop&w=800&q=80",
        "https://images.unsplash.com/photo-1491553895911-0055eca6402d?auto=format&fit=crop&w=800&q=80",
        "https://images.unsplash.com/photo-1523381210434-271e8be1f52b?auto=format&fit=crop&w=800&q=80",
        "https://images.unsplash.com/photo-1560343090-f0409e92791a?auto=format&fit=crop&w=800&q=80",
        "https://images.unsplash.com/photo-1586495777744-4413f21062fa?auto=format&fit=crop&w=800&q=80",
        "https://images.unsplash.com/photo-1596755389378-c31d21fd1273?auto=format&fit=crop&w=800&q=80",
        "https://images.unsplash.com/photo-1580894908361-967195033215?auto=format&fit=crop&w=800&q=80",
        "https://images.unsplash.com/photo-1572635196237-14b3f281503f?auto=format&fit=crop&w=800&q=80",
        "https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?auto=format&fit=crop&w=800&q=80",
        "https://images.unsplash.com/photo-1543163521-1bf539c55dd2?auto=format&fit=crop&w=800&q=80",
    ];

    const mobile_feed_all_products = useMemo(() => {
        const mobile_feed_category_ids = ["hot", "voucher", "look", "free", "beauty"];
        const mobile_feed_products = [];

        for (let mobile_feed_index = 1; mobile_feed_index <= 200; mobile_feed_index += 1) {
            const mobile_feed_category =
                mobile_feed_category_ids[(mobile_feed_index - 1) % mobile_feed_category_ids.length];

            const mobile_feed_title_list = mobile_feed_product_titles[mobile_feed_category];
            const mobile_feed_title =
                mobile_feed_title_list[(mobile_feed_index - 1) % mobile_feed_title_list.length];

            const mobile_feed_image =
                mobile_feed_product_images[(mobile_feed_index - 1) % mobile_feed_product_images.length];

            const mobile_feed_price = 99 + ((mobile_feed_index * 17) % 901);
            const mobile_feed_rating = (3.5 + ((mobile_feed_index * 7) % 15) / 10).toFixed(1);
            const mobile_feed_reviews = 50 + ((mobile_feed_index * 29) % 950);
            const mobile_feed_badge_number = 1 + ((mobile_feed_index - 1) % 12);

            mobile_feed_products.push({
                id: mobile_feed_index,
                category: mobile_feed_category,
                title: `${mobile_feed_title} ${mobile_feed_index}`,
                image: mobile_feed_image,
                price: mobile_feed_price,
                rating: Number(mobile_feed_rating),
                reviews: mobile_feed_reviews,
                badge: `${mobile_feed_badge_number}.${mobile_feed_badge_number}`,
            });
        }

        return mobile_feed_products;
    }, []);

    const [mobile_feed_active_category, setMobileFeedActiveCategory] = useState("all");
    const [mobile_feed_visible_count, setMobileFeedVisibleCount] = useState(8);
    const mobile_feed_loader_ref = useRef(null);

    const mobile_feed_filtered_products = useMemo(() => {
        if (mobile_feed_active_category === "all") {
            return mobile_feed_all_products;
        }

        return mobile_feed_all_products.filter(
            (product) => product.category === mobile_feed_active_category
        );
    }, [mobile_feed_active_category, mobile_feed_all_products]);

    const mobile_feed_visible_products = useMemo(() => {
        return mobile_feed_filtered_products.slice(0, mobile_feed_visible_count);
    }, [mobile_feed_filtered_products, mobile_feed_visible_count]);

    useEffect(() => {
        setMobileFeedVisibleCount(8);
    }, [mobile_feed_active_category]);

    useEffect(() => {
        const mobile_feed_target = mobile_feed_loader_ref.current;
        if (!mobile_feed_target) return;

        const mobile_feed_observer = new IntersectionObserver(
            (entries) => {
                const mobile_feed_first_entry = entries[0];

                if (
                    mobile_feed_first_entry.isIntersecting &&
                    mobile_feed_visible_count < mobile_feed_filtered_products.length
                ) {
                    setMobileFeedVisibleCount((prev) => prev + 8);
                }
            },
            {
                root: null,
                rootMargin: "140px",
                threshold: 0.1,
            }
        );

        mobile_feed_observer.observe(mobile_feed_target);

        return () => {
            mobile_feed_observer.disconnect();
        };
    }, [mobile_feed_visible_count, mobile_feed_filtered_products.length]);

    return (
        <div id="mobile_feed_section">
            <div className="mobile_feed_category_slider">
                {mobile_feed_categories.map((category) => (
                    <button
                        key={category.id}
                        type="button"
                        className={`mobile_feed_category_item ${
                            mobile_feed_active_category === category.id
                                ? "mobile_feed_category_item_active"
                                : ""
                        }`}
                        onClick={() => setMobileFeedActiveCategory(category.id)}
                    >
                        <div className="mobile_feed_category_icon_wrap">
                            <img
                                src={category.icon}
                                alt={category.name}
                                className="mobile_feed_category_icon"
                            />
                        </div>
                        <span className="mobile_feed_category_text">{category.name}</span>
                    </button>
                ))}
            </div>

            <div className="mobile_feed_product_grid">
                {mobile_feed_visible_products.map((product) => (
                    <a
                        href="#"
                        key={product.id}
                        className="mobile_feed_product_card"
                    >
                        <div className="mobile_feed_product_image_wrap">
                            <img
                                src={product.image}
                                alt={product.title}
                                className="mobile_feed_product_image"
                                loading="lazy"
                            />
                        </div>

                        <div className="mobile_feed_product_info">
                            <h3 className="mobile_feed_product_title">
                                <span className="mobile_feed_product_badge">
                                    {product.badge}
                                </span>
                                {product.title}
                            </h3>

                            <div className="mobile_feed_product_price">
                                ৳{product.price}
                            </div>

                            <div className="mobile_feed_product_rating_row">
                                <span className="mobile_feed_product_star">★</span>
                                <span className="mobile_feed_product_rating">
                                    {product.rating}
                                </span>
                                <span className="mobile_feed_product_reviews">
                                    ({product.reviews})
                                </span>
                            </div>
                        </div>
                    </a>
                ))}
            </div>

            <div
                ref={mobile_feed_loader_ref}
                className="mobile_feed_loader_trigger"
            ></div>
        </div>
    );
}
