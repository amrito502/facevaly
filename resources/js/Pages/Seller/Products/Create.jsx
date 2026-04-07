 import React, { useMemo, useRef, useState } from "react";
import { Head, useForm, usePage } from "@inertiajs/react";
import axios from "axios";

function RichEditor({ label, required = false, value, onChange }) {
    const editorRef = useRef(null);

    const exec = (command, commandValue = null) => {
        document.execCommand(command, false, commandValue);
        if (editorRef.current) {
            onChange(editorRef.current.innerHTML);
        }
    };

    return (
        <div className="mb-4">
            <label className="mb-2 block text-sm font-semibold text-slate-700">
                {label} {required && <span className="text-red-500">*</span>}
            </label>

            <div className="overflow-hidden rounded-md border border-slate-200 bg-white">
                <div className="flex flex-wrap gap-2 border-b border-slate-200 bg-slate-50 px-3 py-2">
                    <button type="button" onClick={() => exec("undo")} className="text-sm">↶</button>
                    <button type="button" onClick={() => exec("redo")} className="text-sm">↷</button>
                    <button type="button" onClick={() => exec("bold")} className="text-sm font-bold">B</button>
                    <button type="button" onClick={() => exec("italic")} className="text-sm italic">I</button>
                    <button type="button" onClick={() => exec("underline")} className="text-sm underline">U</button>
                    <button type="button" onClick={() => exec("insertUnorderedList")} className="text-sm">• List</button>
                    <button type="button" onClick={() => exec("insertOrderedList")} className="text-sm">1. List</button>
                </div>

                <div
                    ref={editorRef}
                    contentEditable
                    className="min-h-[150px] px-4 py-3 text-sm text-slate-700 outline-none"
                    dangerouslySetInnerHTML={{ __html: value || "" }}
                    onInput={(e) => onChange(e.currentTarget.innerHTML)}
                />
            </div>
        </div>
    );
}

function UploadBox({ label, note, onChange, preview = null }) {
    return (
        <div className="mb-4">
            <label className="mb-2 block text-sm font-semibold text-slate-700">{label}</label>

            <label className="flex min-h-[220px] cursor-pointer items-center justify-center rounded-md border border-dashed border-slate-300 bg-white text-center">
                <input
                    type="file"
                    className="hidden"
                    onChange={onChange}
                    accept="image/*"
                />
                <div>
                    {preview ? (
                        <img src={preview} alt="preview" className="mx-auto h-40 object-contain" />
                    ) : (
                        <>
                            <div className="text-4xl text-slate-400">☁</div>
                            <p className="mt-2 text-sm text-slate-500">Upload Thumbnail Image</p>
                        </>
                    )}
                </div>
            </label>

            {note && <p className="mt-2 text-xs text-amber-500">{note}</p>}
        </div>
    );
}

function UnitModal({ open, onClose, onSaved }) {
    const [name, setName] = useState("");
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState("");

    if (!open) return null;

    const submit = async () => {
        setLoading(true);
        setError("");

        try {
            const response = await axios.post("/seller/units", { name });

            onSaved(response.data.unit);
            setName("");
            onClose();
        } catch (e) {
            const msg =
                e?.response?.data?.errors?.name?.[0] ||
                e?.response?.data?.message ||
                "Failed to create unit";

            setError(msg);
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
            <div className="w-full max-w-lg rounded-xl bg-white p-6 shadow-xl">
                <div className="mb-5 flex items-center justify-between">
                    <h3 className="text-2xl font-bold text-slate-800">Add Unit</h3>
                    <button type="button" onClick={onClose} className="text-3xl text-slate-400">×</button>
                </div>

                <label className="mb-2 block text-sm font-medium text-slate-700">Unit name</label>
                <input
                    type="text"
                    value={name}
                    onChange={(e) => setName(e.target.value)}
                    className="w-full rounded-md border border-slate-300 px-4 py-3 outline-none"
                    placeholder="Unit name"
                />

                {error && <p className="mt-2 text-sm text-red-500">{error}</p>}

                <div className="mt-6">
                    <button
                        type="button"
                        onClick={submit}
                        disabled={loading}
                        className="rounded-md bg-emerald-500 px-6 py-3 text-white hover:bg-emerald-600"
                    >
                        {loading ? "Saving..." : "Save"}
                    </button>
                </div>
            </div>
        </div>
    );
}

export default function Create({ categories, brands, units: initialUnits }) {
    const { flash } = usePage().props;
    const [units, setUnits] = useState(initialUnits || []);
    const [unitModalOpen, setUnitModalOpen] = useState(false);
    const [thumbnailPreview, setThumbnailPreview] = useState(null);

    const { data, setData, post, processing, errors, reset } = useForm({
        name: "",
        product_type: "single",
        description: "",
        specification: "",
        purchase_price: "",
        regular_price: "",
        discounted_price: "",
        is_retail: true,
        is_wholesale: false,
        wholesale_price: "",
        wholesale_min_qty: "",
        stock_qty: "",
        weight_kg: "",
        warranty_type: "no_warranty",
        warranty_policy: "",
        replacement_warranty_enabled: false,
        replacement_duration: "",
        replacement_duration_unit: "day",
        service_warranty_enabled: false,
        service_duration: "",
        service_duration_unit: "day",
        sku: "",
        barcode: "",
        category_id: "",
        brand_id: "",
        unit_id: "",
        thumbnail: null,
        gallery: [],
    });

    const submit = (e) => {
        e.preventDefault();

        post("/seller/products", {
            forceFormData: true,
            preserveScroll: true,
            onSuccess: () => {
                reset();
                setThumbnailPreview(null);
            },
            onError: (err) => {
                console.log("Validation errors:", err);
            },
        });
    };

    const groupedCategories = useMemo(() => {
        const parents = categories.filter((c) => !c.parent_id);
        const children = categories.filter((c) => c.parent_id);

        return parents.map((parent) => ({
            ...parent,
            children: children.filter((child) => child.parent_id === parent.id),
        }));
    }, [categories]);

    const handleUnitSaved = (unit) => {
        setUnits((prev) => [...prev, unit]);
        setData("unit_id", String(unit.id));
    };

    return (
        <>
            <Head title="Add Product" />

            <UnitModal
                open={unitModalOpen}
                onClose={() => setUnitModalOpen(false)}
                onSaved={handleUnitSaved}
            />

            <div className="min-h-screen bg-slate-100 p-6">
                <div className="mx-auto max-w-[1200px]">
                    <h1 className="mb-6 text-3xl font-bold text-slate-800">Add Product</h1>

                    {flash?.success && (
                        <div className="mb-4 rounded-md bg-emerald-100 px-4 py-3 text-emerald-700">
                            {flash.success}
                        </div>
                    )}

                    <form onSubmit={submit} className="rounded-md bg-white p-6 shadow-sm">
                        <div className="grid grid-cols-1 gap-6 lg:grid-cols-12">
                            <div className="lg:col-span-7">
                                <div className="mb-4">
                                    <label className="mb-2 block text-sm font-semibold text-slate-700">
                                        Product Name <span className="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        value={data.name}
                                        onChange={(e) => setData("name", e.target.value)}
                                        className="w-full rounded-md border border-slate-300 px-4 py-3"
                                        placeholder="Type product name here"
                                    />
                                    {errors.name && <p className="mt-1 text-sm text-red-500">{errors.name}</p>}
                                </div>

                                <RichEditor
                                    label="Description"
                                    required
                                    value={data.description}
                                    onChange={(value) => setData("description", value)}
                                />
                                {errors.description && <p className="-mt-3 mb-4 text-sm text-red-500">{errors.description}</p>}

                                <RichEditor
                                    label="Specification"
                                    value={data.specification}
                                    onChange={(value) => setData("specification", value)}
                                />

                                <div className="mb-4">
                                    <label className="mb-2 block text-sm font-semibold text-slate-700">SKU</label>
                                    <input
                                        type="text"
                                        value={data.sku}
                                        onChange={(e) => setData("sku", e.target.value)}
                                        className="w-full rounded-md border border-slate-300 px-4 py-3"
                                        placeholder="Product SKU"
                                    />
                                    {errors.sku && <p className="mt-1 text-sm text-red-500">{errors.sku}</p>}
                                </div>

                                <div className="mb-4">
                                    <label className="mb-2 block text-sm font-semibold text-slate-700">
                                        Category <span className="text-red-500">*</span>
                                    </label>
                                    <select
                                        value={data.category_id}
                                        onChange={(e) => setData("category_id", e.target.value)}
                                        className="w-full rounded-md border border-slate-300 px-4 py-3"
                                    >
                                        <option value="">Select Category</option>
                                        {groupedCategories.map((parent) => (
                                            <React.Fragment key={parent.id}>
                                                <option value={parent.id}>{parent.name}</option>
                                                {parent.children.map((child) => (
                                                    <option key={child.id} value={child.id}>
                                                        └ {child.name}
                                                    </option>
                                                ))}
                                            </React.Fragment>
                                        ))}
                                    </select>
                                    {errors.category_id && <p className="mt-1 text-sm text-red-500">{errors.category_id}</p>}
                                </div>

                                <div className="mb-4">
                                    <label className="mb-2 block text-sm font-semibold text-slate-700">Brand</label>
                                    <select
                                        value={data.brand_id}
                                        onChange={(e) => setData("brand_id", e.target.value)}
                                        className="w-full rounded-md border border-slate-300 px-4 py-3"
                                    >
                                        <option value="">Select Brand</option>
                                        {brands.map((brand) => (
                                            <option key={brand.id} value={brand.id}>
                                                {brand.name}
                                            </option>
                                        ))}
                                    </select>
                                </div>

                                <div className="mb-4">
                                    <label className="mb-2 block text-sm font-semibold text-slate-700">Unit</label>
                                    <div className="flex gap-2">
                                        <select
                                            value={data.unit_id}
                                            onChange={(e) => setData("unit_id", e.target.value)}
                                            className="w-full rounded-md border border-slate-300 px-4 py-3"
                                        >
                                            <option value="">Select Unit</option>
                                            {units.map((unit) => (
                                                <option key={unit.id} value={unit.id}>
                                                    {unit.name}{unit.short_name ? ` (${unit.short_name})` : ""}
                                                </option>
                                            ))}
                                        </select>

                                        <button
                                            type="button"
                                            onClick={() => setUnitModalOpen(true)}
                                            className="rounded-md border border-slate-300 px-4 text-xl"
                                        >
                                            +
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div className="lg:col-span-5">
                                <div className="mb-4">
                                    <label className="mb-2 block text-sm font-semibold text-slate-700">Product Type</label>
                                    <div className="flex gap-6">
                                        <label className="flex items-center gap-2">
                                            <input
                                                type="radio"
                                                name="product_type"
                                                checked={data.product_type === "single"}
                                                onChange={() => setData("product_type", "single")}
                                            />
                                            <span>Single</span>
                                        </label>

                                        <label className="flex items-center gap-2">
                                            <input
                                                type="radio"
                                                name="product_type"
                                                checked={data.product_type === "variant"}
                                                onChange={() => setData("product_type", "variant")}
                                            />
                                            <span>Variant</span>
                                        </label>
                                    </div>

                                    {data.product_type === "variant" && (
                                        <div className="mt-4 rounded-md bg-blue-50 p-4 text-sm text-blue-600">
                                            You can manage variations after adding a product on inventory.
                                        </div>
                                    )}
                                </div>

                                {data.product_type === "single" && (
                                    <>
                                        <div className="mb-4">
                                            <label className="mb-2 block text-sm font-semibold text-slate-700">
                                                Purchase Price
                                            </label>
                                            <input
                                                type="number"
                                                step="0.01"
                                                value={data.purchase_price}
                                                onChange={(e) => setData("purchase_price", e.target.value)}
                                                className="w-full rounded-md border border-slate-300 px-4 py-3"
                                                placeholder="Purchase Price"
                                            />
                                        </div>

                                        <div className="mb-4">
                                            <label className="mb-2 block text-sm font-semibold text-slate-700">Selling type</label>
                                            <div className="flex gap-5">
                                                <label className="flex items-center gap-2">
                                                    <input
                                                        type="checkbox"
                                                        checked={data.is_retail}
                                                        onChange={(e) => setData("is_retail", e.target.checked)}
                                                    />
                                                    <span>Retail</span>
                                                </label>
                                                <label className="flex items-center gap-2">
                                                    <input
                                                        type="checkbox"
                                                        checked={data.is_wholesale}
                                                        onChange={(e) => setData("is_wholesale", e.target.checked)}
                                                    />
                                                    <span>Wholesale</span>
                                                </label>
                                            </div>
                                        </div>

                                        <div className="mb-4 grid grid-cols-2 gap-4">
                                            <div>
                                                <label className="mb-2 block text-sm font-semibold text-slate-700">
                                                    Regular Price
                                                </label>
                                                <input
                                                    type="number"
                                                    step="0.01"
                                                    value={data.regular_price}
                                                    onChange={(e) => setData("regular_price", e.target.value)}
                                                    className="w-full rounded-md border border-slate-300 px-4 py-3"
                                                    placeholder="Regular Price"
                                                />
                                            </div>

                                            <div>
                                                <label className="mb-2 block text-sm font-semibold text-slate-700">
                                                    Discounted Price
                                                </label>
                                                <input
                                                    type="number"
                                                    step="0.01"
                                                    value={data.discounted_price}
                                                    onChange={(e) => setData("discounted_price", e.target.value)}
                                                    className="w-full rounded-md border border-slate-300 px-4 py-3"
                                                    placeholder="Discounted Price"
                                                />
                                            </div>
                                        </div>

                                        {data.is_wholesale && (
                                            <div className="mb-4 grid grid-cols-2 gap-4">
                                                <div>
                                                    <label className="mb-2 block text-sm font-semibold text-slate-700">Wholesale Price</label>
                                                    <input
                                                        type="number"
                                                        step="0.01"
                                                        value={data.wholesale_price}
                                                        onChange={(e) => setData("wholesale_price", e.target.value)}
                                                        className="w-full rounded-md border border-slate-300 px-4 py-3"
                                                        placeholder="Wholesale Price"
                                                    />
                                                </div>
                                                <div>
                                                    <label className="mb-2 block text-sm font-semibold text-slate-700">Wholesale Min. QTY</label>
                                                    <input
                                                        type="number"
                                                        value={data.wholesale_min_qty}
                                                        onChange={(e) => setData("wholesale_min_qty", e.target.value)}
                                                        className="w-full rounded-md border border-slate-300 px-4 py-3"
                                                        placeholder="Wholesale Min. QTY"
                                                    />
                                                </div>
                                            </div>
                                        )}

                                        <div className="mb-4">
                                            <label className="mb-2 block text-sm font-semibold text-slate-700">Stock Quantity</label>
                                            <input
                                                type="number"
                                                value={data.stock_qty}
                                                onChange={(e) => setData("stock_qty", e.target.value)}
                                                className="w-full rounded-md border border-slate-300 px-4 py-3"
                                                placeholder="Stock Quantity"
                                            />
                                        </div>
                                    </>
                                )}

                                <div className="mb-4">
                                    <label className="mb-2 block text-sm font-semibold text-slate-700">Weight(Kg)</label>
                                    <input
                                        type="number"
                                        step="0.001"
                                        value={data.weight_kg}
                                        onChange={(e) => setData("weight_kg", e.target.value)}
                                        className="w-full rounded-md border border-slate-300 px-4 py-3"
                                        placeholder="Weight(Kg)"
                                    />
                                </div>

                                <div className="mb-4">
                                    <label className="mb-2 block text-sm font-semibold text-slate-700">Warranty</label>
                                    <div className="flex gap-6">
                                        <label className="flex items-center gap-2">
                                            <input
                                                type="radio"
                                                checked={data.warranty_type === "no_warranty"}
                                                onChange={() => setData("warranty_type", "no_warranty")}
                                            />
                                            <span>No Warranty</span>
                                        </label>
                                        <label className="flex items-center gap-2">
                                            <input
                                                type="radio"
                                                checked={data.warranty_type === "seller_warranty"}
                                                onChange={() => setData("warranty_type", "seller_warranty")}
                                            />
                                            <span>Seller Warranty</span>
                                        </label>
                                    </div>
                                </div>

                                {data.warranty_type === "seller_warranty" && (
                                    <div className="mb-4 rounded-md bg-slate-50 p-4">
                                        <h4 className="mb-4 text-lg font-semibold text-slate-800">Warranty Type</h4>

                                        <div className="mb-4 grid grid-cols-[24px_1fr_90px_100px] items-center gap-3">
                                            <input
                                                type="checkbox"
                                                checked={data.replacement_warranty_enabled}
                                                onChange={(e) => setData("replacement_warranty_enabled", e.target.checked)}
                                            />
                                            <div>
                                                <div className="font-medium">Replacement Warranty</div>
                                                <div className="text-xs text-slate-500">Covers repairs and maintenance services</div>
                                            </div>
                                            <input
                                                type="number"
                                                value={data.replacement_duration}
                                                onChange={(e) => setData("replacement_duration", e.target.value)}
                                                className="rounded-md border border-slate-300 px-3 py-2"
                                            />
                                            <select
                                                value={data.replacement_duration_unit}
                                                onChange={(e) => setData("replacement_duration_unit", e.target.value)}
                                                className="rounded-md border border-slate-300 px-3 py-2"
                                            >
                                                <option value="day">Day</option>
                                                <option value="month">Month</option>
                                                <option value="year">Year</option>
                                            </select>
                                        </div>

                                        <div className="mb-4 grid grid-cols-[24px_1fr_90px_100px] items-center gap-3">
                                            <input
                                                type="checkbox"
                                                checked={data.service_warranty_enabled}
                                                onChange={(e) => setData("service_warranty_enabled", e.target.checked)}
                                            />
                                            <div>
                                                <div className="font-medium">Service Warranty</div>
                                                <div className="text-xs text-slate-500">Covers repairs and maintenance services</div>
                                            </div>
                                            <input
                                                type="number"
                                                value={data.service_duration}
                                                onChange={(e) => setData("service_duration", e.target.value)}
                                                className="rounded-md border border-slate-300 px-3 py-2"
                                            />
                                            <select
                                                value={data.service_duration_unit}
                                                onChange={(e) => setData("service_duration_unit", e.target.value)}
                                                className="rounded-md border border-slate-300 px-3 py-2"
                                            >
                                                <option value="day">Day</option>
                                                <option value="month">Month</option>
                                                <option value="year">Year</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label className="mb-2 block text-sm font-semibold text-slate-700">Warranty Policy</label>
                                            <input
                                                type="text"
                                                value={data.warranty_policy}
                                                onChange={(e) => setData("warranty_policy", e.target.value)}
                                                className="w-full rounded-md border border-slate-300 px-4 py-3"
                                                placeholder="Warranty Policy if any"
                                            />
                                        </div>
                                    </div>
                                )}

                                <UploadBox
                                    label="Upload Thumbnail Image (380 x 380)"
                                    preview={thumbnailPreview}
                                    onChange={(e) => {
                                        const file = e.target.files[0];
                                        setData("thumbnail", file);
                                        if (file) {
                                            setThumbnailPreview(URL.createObjectURL(file));
                                        }
                                    }}
                                />

                                <div className="mb-4">
                                    <label className="mb-2 block text-sm font-semibold text-slate-700">
                                        Product gallery (Formats: JPG, JPEG, PNG - Max : 10MB)
                                    </label>
                                    <input
                                        type="file"
                                        multiple
                                        accept="image/*"
                                        onChange={(e) => setData("gallery", Array.from(e.target.files))}
                                        className="block w-full rounded-md border border-slate-300 px-4 py-3"
                                    />
                                    <p className="mt-2 text-xs text-amber-500">Please use original product image.</p>
                                </div>
                            </div>
                        </div>

                        <div className="mt-8 flex justify-end border-t pt-6">
                            <button
                                type="submit"
                                disabled={processing}
                                className="rounded-md bg-emerald-500 px-8 py-3 font-semibold text-white hover:bg-emerald-600"
                            >
                                {processing ? "Saving..." : "Add Product"}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </>
    );
}