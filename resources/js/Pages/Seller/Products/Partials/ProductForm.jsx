import React from "react";
import { Link } from "@inertiajs/react";

export default function ProductForm({
    data,
    setData,
    post,
    put,
    processing,
    errors,
    progress,
    categories,
    brands,
    attributes,
    shop,
    submitLabel = "Save",
    isEdit = false,
    product = null,
}) {
    const submit = (e) => {
        e.preventDefault();

        if (isEdit && product) {
            put(route("seller.products.update", product.id));
        } else {
            post(route("seller.products.store"));
        }
    };

    const addAttributeRow = () => {
        setData("product_attributes", [
            ...data.product_attributes,
            {
                attribute_id: "",
                attribute_value_id: "",
                value_text: "",
            },
        ]);
    };

    const updateAttributeRow = (index, field, value) => {
        const rows = [...data.product_attributes];
        rows[index][field] = value;

        if (field === "attribute_id") {
            rows[index].attribute_value_id = "";
        }

        setData("product_attributes", rows);
    };

    const removeAttributeRow = (index) => {
        setData(
            "product_attributes",
            data.product_attributes.filter((_, i) => i !== index)
        );
    };

    const addVariant = () => {
        setData("variants", [
            ...data.variants,
            {
                name: "",
                price: "",
                sale_price: "",
                cost_price: "",
                weight: "",
                stock_qty: 0,
                track_stock: true,
                is_default: false,
                is_active: true,
                values: [],
            },
        ]);
    };

    const updateVariant = (index, field, value) => {
        const rows = [...data.variants];
        rows[index][field] = value;
        setData("variants", rows);
    };

    const removeVariant = (index) => {
        setData(
            "variants",
            data.variants.filter((_, i) => i !== index)
        );
    };

    const addVariantValue = (variantIndex) => {
        const rows = [...data.variants];
        rows[variantIndex].values.push({
            attribute_id: "",
            attribute_value_id: "",
        });
        setData("variants", rows);
    };

    const updateVariantValue = (variantIndex, valueIndex, field, value) => {
        const rows = [...data.variants];
        rows[variantIndex].values[valueIndex][field] = value;

        if (field === "attribute_id") {
            rows[variantIndex].values[valueIndex].attribute_value_id = "";
        }

        setData("variants", rows);
    };

    const removeVariantValue = (variantIndex, valueIndex) => {
        const rows = [...data.variants];
        rows[variantIndex].values = rows[variantIndex].values.filter((_, i) => i !== valueIndex);
        setData("variants", rows);
    };

    const handleImageChange = (e) => {
        setData("images", Array.from(e.target.files));
    };

    const toggleDeleteMedia = (id) => {
        const selected = data.delete_media || [];

        if (selected.includes(id)) {
            setData(
                "delete_media",
                selected.filter((item) => item !== id)
            );
        } else {
            setData("delete_media", [...selected, id]);
        }
    };

    const findAttributeValues = (attributeId) => {
        const attr = attributes.find((item) => String(item.id) === String(attributeId));
        return attr?.values || [];
    };

    return (
        <form onSubmit={submit} className="space-y-8">
            <div className="bg-white rounded-xl shadow-sm border p-6">
                <div className="flex items-center justify-between mb-6">
                    <div>
                        <h1 className="text-2xl font-bold">
                            {isEdit ? "Edit Product" : "Create Product"}
                        </h1>
                        <p className="text-sm text-gray-500 mt-1">
                            Shop: <strong>{shop.shop_name}</strong>
                        </p>
                    </div>

                    <Link
                        href={route("seller.products.index")}
                        className="px-4 py-2 border rounded-lg text-sm"
                    >
                        Back
                    </Link>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label className="block text-sm font-medium mb-1">Product Name</label>
                        <input
                            type="text"
                            className="w-full border rounded-lg px-3 py-2"
                            value={data.name}
                            onChange={(e) => setData("name", e.target.value)}
                        />
                        {errors.name && <p className="text-red-500 text-sm mt-1">{errors.name}</p>}
                    </div>

                    <div>
                        <label className="block text-sm font-medium mb-1">Category</label>
                        <select
                            className="w-full border rounded-lg px-3 py-2"
                            value={data.category_id}
                            onChange={(e) => setData("category_id", e.target.value)}
                        >
                            <option value="">Select Category</option>
                            {categories.map((item) => (
                                <option key={item.id} value={item.id}>
                                    {item.name}
                                </option>
                            ))}
                        </select>
                    </div>

                    <div>
                        <label className="block text-sm font-medium mb-1">Brand</label>
                        <select
                            className="w-full border rounded-lg px-3 py-2"
                            value={data.brand_id}
                            onChange={(e) => setData("brand_id", e.target.value)}
                        >
                            <option value="">Select Brand</option>
                            {brands.map((item) => (
                                <option key={item.id} value={item.id}>
                                    {item.name}
                                </option>
                            ))}
                        </select>
                    </div>

                    <div>
                        <label className="block text-sm font-medium mb-1">Base Price</label>
                        <input
                            type="number"
                            step="0.01"
                            className="w-full border rounded-lg px-3 py-2"
                            value={data.base_price}
                            onChange={(e) => setData("base_price", e.target.value)}
                        />
                        {errors.base_price && <p className="text-red-500 text-sm mt-1">{errors.base_price}</p>}
                    </div>

                    <div>
                        <label className="block text-sm font-medium mb-1">Sale Price</label>
                        <input
                            type="number"
                            step="0.01"
                            className="w-full border rounded-lg px-3 py-2"
                            value={data.sale_price}
                            onChange={(e) => setData("sale_price", e.target.value)}
                        />
                    </div>

                    <div>
                        <label className="block text-sm font-medium mb-1">Cost Price</label>
                        <input
                            type="number"
                            step="0.01"
                            className="w-full border rounded-lg px-3 py-2"
                            value={data.cost_price}
                            onChange={(e) => setData("cost_price", e.target.value)}
                        />
                    </div>

                    <div>
                        <label className="block text-sm font-medium mb-1">Weight</label>
                        <input
                            type="number"
                            step="0.01"
                            className="w-full border rounded-lg px-3 py-2"
                            value={data.weight}
                            onChange={(e) => setData("weight", e.target.value)}
                        />
                    </div>

                    <div>
                        <label className="block text-sm font-medium mb-1">Product Type</label>
                        <select
                            className="w-full border rounded-lg px-3 py-2"
                            value={data.product_type}
                            onChange={(e) => setData("product_type", e.target.value)}
                        >
                            <option value="simple">Simple</option>
                            <option value="variable">Variable</option>
                            <option value="digital">Digital</option>
                        </select>
                    </div>

                    <div>
                        <label className="block text-sm font-medium mb-1">Status</label>
                        <select
                            className="w-full border rounded-lg px-3 py-2"
                            value={data.status}
                            onChange={(e) => setData("status", e.target.value)}
                        >
                            <option value="draft">Draft</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                            <option value="published">Published</option>
                            <option value="unpublished">Unpublished</option>
                        </select>
                    </div>

                    <div>
                        <label className="block text-sm font-medium mb-1">Published At</label>
                        <input
                            type="datetime-local"
                            className="w-full border rounded-lg px-3 py-2"
                            value={data.published_at}
                            onChange={(e) => setData("published_at", e.target.value)}
                        />
                    </div>

                    <div>
                        <label className="block text-sm font-medium mb-1">Min Order Qty</label>
                        <input
                            type="number"
                            className="w-full border rounded-lg px-3 py-2"
                            value={data.min_order_qty}
                            onChange={(e) => setData("min_order_qty", e.target.value)}
                        />
                    </div>

                    <div>
                        <label className="block text-sm font-medium mb-1">Max Order Qty</label>
                        <input
                            type="number"
                            className="w-full border rounded-lg px-3 py-2"
                            value={data.max_order_qty}
                            onChange={(e) => setData("max_order_qty", e.target.value)}
                        />
                    </div>

                    <div>
                        <label className="block text-sm font-medium mb-1">Length</label>
                        <input
                            type="number"
                            step="0.01"
                            className="w-full border rounded-lg px-3 py-2"
                            value={data.length}
                            onChange={(e) => setData("length", e.target.value)}
                        />
                    </div>

                    <div>
                        <label className="block text-sm font-medium mb-1">Width</label>
                        <input
                            type="number"
                            step="0.01"
                            className="w-full border rounded-lg px-3 py-2"
                            value={data.width}
                            onChange={(e) => setData("width", e.target.value)}
                        />
                    </div>

                    <div>
                        <label className="block text-sm font-medium mb-1">Height</label>
                        <input
                            type="number"
                            step="0.01"
                            className="w-full border rounded-lg px-3 py-2"
                            value={data.height}
                            onChange={(e) => setData("height", e.target.value)}
                        />
                    </div>

                    <div className="md:col-span-2">
                        <label className="block text-sm font-medium mb-1">Short Description</label>
                        <textarea
                            rows="3"
                            className="w-full border rounded-lg px-3 py-2"
                            value={data.short_description}
                            onChange={(e) => setData("short_description", e.target.value)}
                        />
                    </div>

                    <div className="md:col-span-2">
                        <label className="block text-sm font-medium mb-1">Description</label>
                        <textarea
                            rows="5"
                            className="w-full border rounded-lg px-3 py-2"
                            value={data.description}
                            onChange={(e) => setData("description", e.target.value)}
                        />
                    </div>
                </div>

                <div className="flex flex-wrap gap-6 mt-6">
                    <label className="flex items-center gap-2">
                        <input
                            type="checkbox"
                            checked={data.is_featured}
                            onChange={(e) => setData("is_featured", e.target.checked)}
                        />
                        Featured
                    </label>

                    <label className="flex items-center gap-2">
                        <input
                            type="checkbox"
                            checked={data.is_refundable}
                            onChange={(e) => setData("is_refundable", e.target.checked)}
                        />
                        Refundable
                    </label>

                    <label className="flex items-center gap-2">
                        <input
                            type="checkbox"
                            checked={data.track_stock}
                            onChange={(e) => setData("track_stock", e.target.checked)}
                        />
                        Track Stock
                    </label>
                </div>
            </div>

            <div className="bg-white rounded-xl shadow-sm border p-6">
                <div className="flex items-center justify-between mb-4">
                    <h2 className="text-lg font-semibold">Product Images</h2>
                </div>

                {isEdit && product?.media?.length > 0 && (
                    <div className="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                        {product.media.map((media) => (
                            <div key={media.id} className="border rounded-lg p-2">
                                <img
                                    src={`/storage/${media.file_path}`}
                                    alt=""
                                    className="w-full h-28 object-cover rounded"
                                />
                                <label className="mt-2 flex items-center gap-2 text-sm">
                                    <input
                                        type="checkbox"
                                        checked={(data.delete_media || []).includes(media.id)}
                                        onChange={() => toggleDeleteMedia(media.id)}
                                    />
                                    Delete
                                </label>
                            </div>
                        ))}
                    </div>
                )}

                <input type="file" multiple onChange={handleImageChange} />
                {errors["images.0"] && (
                    <p className="text-red-500 text-sm mt-1">{errors["images.0"]}</p>
                )}

                {progress && (
                    <p className="text-sm text-blue-600 mt-2">
                        Uploading: {progress.percentage}%
                    </p>
                )}
            </div>

            <div className="bg-white rounded-xl shadow-sm border p-6">
                <div className="flex items-center justify-between mb-4">
                    <h2 className="text-lg font-semibold">Product Attributes</h2>
                    <button
                        type="button"
                        onClick={addAttributeRow}
                        className="px-4 py-2 border rounded-lg text-sm"
                    >
                        Add Attribute
                    </button>
                </div>

                <div className="space-y-4">
                    {data.product_attributes.map((row, index) => {
                        const values = findAttributeValues(row.attribute_id);

                        return (
                            <div key={index} className="grid grid-cols-1 md:grid-cols-4 gap-4 items-start">
                                <select
                                    className="border rounded-lg px-3 py-2"
                                    value={row.attribute_id}
                                    onChange={(e) =>
                                        updateAttributeRow(index, "attribute_id", e.target.value)
                                    }
                                >
                                    <option value="">Select Attribute</option>
                                    {attributes.map((item) => (
                                        <option key={item.id} value={item.id}>
                                            {item.name}
                                        </option>
                                    ))}
                                </select>

                                <select
                                    className="border rounded-lg px-3 py-2"
                                    value={row.attribute_value_id}
                                    onChange={(e) =>
                                        updateAttributeRow(index, "attribute_value_id", e.target.value)
                                    }
                                >
                                    <option value="">Select Value</option>
                                    {values.map((value) => (
                                        <option key={value.id} value={value.id}>
                                            {value.value}
                                        </option>
                                    ))}
                                </select>

                                <input
                                    type="text"
                                    placeholder="Custom value text"
                                    className="border rounded-lg px-3 py-2"
                                    value={row.value_text}
                                    onChange={(e) =>
                                        updateAttributeRow(index, "value_text", e.target.value)
                                    }
                                />

                                <button
                                    type="button"
                                    onClick={() => removeAttributeRow(index)}
                                    className="px-4 py-2 border border-red-500 text-red-500 rounded-lg"
                                >
                                    Remove
                                </button>
                            </div>
                        );
                    })}
                </div>
            </div>

            <div className="bg-white rounded-xl shadow-sm border p-6">
                <div className="flex items-center justify-between mb-4">
                    <h2 className="text-lg font-semibold">Variants</h2>
                    <button
                        type="button"
                        onClick={addVariant}
                        className="px-4 py-2 border rounded-lg text-sm"
                    >
                        Add Variant
                    </button>
                </div>

                <div className="space-y-6">
                    {data.variants.map((variant, variantIndex) => (
                        <div key={variantIndex} className="border rounded-xl p-4">
                            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <input
                                    type="text"
                                    placeholder="Variant name"
                                    className="border rounded-lg px-3 py-2"
                                    value={variant.name}
                                    onChange={(e) =>
                                        updateVariant(variantIndex, "name", e.target.value)
                                    }
                                />

                                <input
                                    type="number"
                                    step="0.01"
                                    placeholder="Price"
                                    className="border rounded-lg px-3 py-2"
                                    value={variant.price}
                                    onChange={(e) =>
                                        updateVariant(variantIndex, "price", e.target.value)
                                    }
                                />

                                <input
                                    type="number"
                                    step="0.01"
                                    placeholder="Sale price"
                                    className="border rounded-lg px-3 py-2"
                                    value={variant.sale_price}
                                    onChange={(e) =>
                                        updateVariant(variantIndex, "sale_price", e.target.value)
                                    }
                                />

                                <input
                                    type="number"
                                    step="0.01"
                                    placeholder="Cost price"
                                    className="border rounded-lg px-3 py-2"
                                    value={variant.cost_price}
                                    onChange={(e) =>
                                        updateVariant(variantIndex, "cost_price", e.target.value)
                                    }
                                />

                                <input
                                    type="number"
                                    step="0.01"
                                    placeholder="Weight"
                                    className="border rounded-lg px-3 py-2"
                                    value={variant.weight}
                                    onChange={(e) =>
                                        updateVariant(variantIndex, "weight", e.target.value)
                                    }
                                />

                                <input
                                    type="number"
                                    placeholder="Stock qty"
                                    className="border rounded-lg px-3 py-2"
                                    value={variant.stock_qty}
                                    onChange={(e) =>
                                        updateVariant(variantIndex, "stock_qty", e.target.value)
                                    }
                                />
                            </div>

                            <div className="flex flex-wrap gap-4 mt-4">
                                <label className="flex items-center gap-2">
                                    <input
                                        type="checkbox"
                                        checked={variant.track_stock}
                                        onChange={(e) =>
                                            updateVariant(variantIndex, "track_stock", e.target.checked)
                                        }
                                    />
                                    Track Stock
                                </label>

                                <label className="flex items-center gap-2">
                                    <input
                                        type="checkbox"
                                        checked={variant.is_default}
                                        onChange={(e) =>
                                            updateVariant(variantIndex, "is_default", e.target.checked)
                                        }
                                    />
                                    Default
                                </label>

                                <label className="flex items-center gap-2">
                                    <input
                                        type="checkbox"
                                        checked={variant.is_active}
                                        onChange={(e) =>
                                            updateVariant(variantIndex, "is_active", e.target.checked)
                                        }
                                    />
                                    Active
                                </label>
                            </div>

                            <div className="mt-5">
                                <div className="flex items-center justify-between mb-3">
                                    <h3 className="font-medium">Variant Values</h3>
                                    <button
                                        type="button"
                                        onClick={() => addVariantValue(variantIndex)}
                                        className="px-3 py-2 border rounded-lg text-sm"
                                    >
                                        Add Value
                                    </button>
                                </div>

                                <div className="space-y-3">
                                    {variant.values.map((value, valueIndex) => {
                                        const values = findAttributeValues(value.attribute_id);

                                        return (
                                            <div key={valueIndex} className="grid grid-cols-1 md:grid-cols-3 gap-3">
                                                <select
                                                    className="border rounded-lg px-3 py-2"
                                                    value={value.attribute_id}
                                                    onChange={(e) =>
                                                        updateVariantValue(
                                                            variantIndex,
                                                            valueIndex,
                                                            "attribute_id",
                                                            e.target.value
                                                        )
                                                    }
                                                >
                                                    <option value="">Select Attribute</option>
                                                    {attributes.map((item) => (
                                                        <option key={item.id} value={item.id}>
                                                            {item.name}
                                                        </option>
                                                    ))}
                                                </select>

                                                <select
                                                    className="border rounded-lg px-3 py-2"
                                                    value={value.attribute_value_id}
                                                    onChange={(e) =>
                                                        updateVariantValue(
                                                            variantIndex,
                                                            valueIndex,
                                                            "attribute_value_id",
                                                            e.target.value
                                                        )
                                                    }
                                                >
                                                    <option value="">Select Value</option>
                                                    {values.map((item) => (
                                                        <option key={item.id} value={item.id}>
                                                            {item.value}
                                                        </option>
                                                    ))}
                                                </select>

                                                <button
                                                    type="button"
                                                    onClick={() => removeVariantValue(variantIndex, valueIndex)}
                                                    className="px-4 py-2 border border-red-500 text-red-500 rounded-lg"
                                                >
                                                    Remove
                                                </button>
                                            </div>
                                        );
                                    })}
                                </div>
                            </div>

                            <div className="mt-4">
                                <button
                                    type="button"
                                    onClick={() => removeVariant(variantIndex)}
                                    className="px-4 py-2 border border-red-500 text-red-500 rounded-lg"
                                >
                                    Remove Variant
                                </button>
                            </div>
                        </div>
                    ))}
                </div>
            </div>

            <div className="flex justify-end">
                <button
                    type="submit"
                    disabled={processing}
                    className="bg-black text-white px-6 py-3 rounded-lg"
                >
                    {processing ? "Saving..." : submitLabel}
                </button>
            </div>
        </form>
    );
}