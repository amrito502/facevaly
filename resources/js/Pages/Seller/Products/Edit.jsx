import React from "react";
import { Head, useForm } from "@inertiajs/react";
import ProductForm from "./Partials/ProductForm";

export default function Edit({ product, shop, categories, brands, attributes }) {
    const form = useForm({
        name: product.name || "",
        category_id: product.category_id || "",
        brand_id: product.brand_id || "",
        short_description: product.short_description || "",
        description: product.description || "",
        base_price: product.base_price || "",
        sale_price: product.sale_price || "",
        cost_price: product.cost_price || "",
        weight: product.weight || "",
        length: product.length || "",
        width: product.width || "",
        height: product.height || "",
        product_type: product.product_type || "simple",
        status: product.status || "draft",
        min_order_qty: product.min_order_qty || 1,
        max_order_qty: product.max_order_qty || "",
        published_at: product.published_at
            ? new Date(product.published_at).toISOString().slice(0, 16)
            : "",
        is_featured: !!product.is_featured,
        is_refundable: !!product.is_refundable,
        track_stock: !!product.track_stock,
        images: [],
        delete_media: [],
        product_attributes: (product.attributes || []).map((item) => ({
            attribute_id: item.attribute_id || "",
            attribute_value_id: item.attribute_value_id || "",
            value_text: item.value_text || "",
        })),
        variants: (product.variants || []).map((variant) => ({
            name: variant.name || "",
            price: variant.price || "",
            sale_price: variant.sale_price || "",
            cost_price: variant.cost_price || "",
            weight: variant.weight || "",
            stock_qty: variant.stock_qty || 0,
            track_stock: !!variant.track_stock,
            is_default: !!variant.is_default,
            is_active: !!variant.is_active,
            values: (variant.values || []).map((value) => ({
                attribute_id: value.attribute_id || "",
                attribute_value_id: value.attribute_value_id || "",
            })),
        })),
    });

    return (
        <>
            <Head title="Edit Product" />

            <div className="max-w-7xl mx-auto p-6">
                <ProductForm
                    {...form}
                    categories={categories}
                    brands={brands}
                    attributes={attributes}
                    shop={shop}
                    submitLabel="Update Product"
                    isEdit={true}
                    product={product}
                />
            </div>
        </>
    );
}