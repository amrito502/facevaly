@props([
    'product' => null,
    'shops' => [],
    'categories' => [],
    'brands' => [],
    'attributeList' => [],
    'submitText' => 'Save',
])

@php
    $existingProductAttributes = old('product_attributes');
    if (is_null($existingProductAttributes) && $product) {
        $existingProductAttributes = $product->attributes->map(function ($row) {
            return [
                'attribute_id' => $row->attribute_id,
                'attribute_value_id' => $row->attribute_value_id,
                'value_text' => $row->value_text,
            ];
        })->values()->toArray();
    }
    $existingProductAttributes = $existingProductAttributes ?? [];

    $existingVariants = old('variants');
    if (is_null($existingVariants) && $product) {
        $existingVariants = $product->variants->map(function ($variant) {
            return [
                'name' => $variant->name,
                'sku' => $variant->sku,
                'barcode' => $variant->barcode,
                'price' => $variant->price,
                'sale_price' => $variant->sale_price,
                'cost_price' => $variant->cost_price,
                'weight' => $variant->weight,
                'stock_qty' => $variant->stock_qty,
                'track_stock' => $variant->track_stock ? 1 : 0,
                'is_default' => $variant->is_default ? 1 : 0,
                'is_active' => $variant->is_active ? 1 : 0,
                'values' => $variant->values->map(function ($value) {
                    return [
                        'attribute_id' => $value->attribute_id,
                        'attribute_value_id' => $value->attribute_value_id,
                    ];
                })->values()->toArray(),
            ];
        })->values()->toArray();
    }
    $existingVariants = $existingVariants ?? [];

    $attributesJson = collect($attributeList)->map(function ($attribute) {
        return [
            'id' => $attribute->id,
            'name' => $attribute->name,
            'is_variation' => $attribute->is_variation,
            'values' => $attribute->values->map(function ($value) {
                return [
                    'id' => $value->id,
                    'value' => $value->value,
                ];
            })->values()->toArray(),
        ];
    })->values()->toArray();
@endphp

<div class="space-y-8">
    <div class="rounded-[18px] border border-gray-200 p-5">
        <h3 class="mb-4 text-[18px] font-semibold text-gray-900">Basic Information</h3>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <div>
                <label for="shop_id" class="mb-2 block text-[15px] font-semibold text-gray-900">Shop</label>
                <select id="shop_id" name="shop_id" class="h-[48px] w-full rounded-[12px] border border-gray-300 bg-white px-4 text-[15px] text-gray-700 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-200">
                    <option value="">Select Shop</option>
                    @foreach($shops as $shop)
                        <option value="{{ $shop->id }}" @selected(old('shop_id', $product?->shop_id) == $shop->id)>
                            {{ $shop->shop_name }}
                        </option>
                    @endforeach
                </select>
                @error('shop_id') <p class="mt-2 text-[14px] text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="category_id" class="mb-2 block text-[15px] font-semibold text-gray-900">Category</label>
                <select id="category_id" name="category_id" class="h-[48px] w-full rounded-[12px] border border-gray-300 bg-white px-4 text-[15px] text-gray-700 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-200">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(old('category_id', $product?->category_id) == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="brand_id" class="mb-2 block text-[15px] font-semibold text-gray-900">Brand</label>
                <select id="brand_id" name="brand_id" class="h-[48px] w-full rounded-[12px] border border-gray-300 bg-white px-4 text-[15px] text-gray-700 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-200">
                    <option value="">Select Brand</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" @selected(old('brand_id', $product?->brand_id) == $brand->id)>{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <label for="name" class="mb-2 block text-[15px] font-semibold text-gray-900">Product Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $product?->name) }}"
                       class="h-[48px] w-full rounded-[12px] border bg-white px-4 text-[15px] text-gray-700 outline-none @error('name') border-red-300 focus:border-red-400 focus:ring-2 focus:ring-red-100 @else border-gray-300 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 @enderror">
                @error('name') <p class="mt-2 text-[14px] text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="slug" class="mb-2 block text-[15px] font-semibold text-gray-900">Slug</label>
                <input type="text" id="slug" name="slug" value="{{ old('slug', $product?->slug) }}"
                       class="h-[48px] w-full rounded-[12px] border bg-white px-4 text-[15px] text-gray-700 outline-none @error('slug') border-red-300 focus:border-red-400 focus:ring-2 focus:ring-red-100 @else border-gray-300 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 @enderror">
                @error('slug') <p class="mt-2 text-[14px] text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="sku" class="mb-2 block text-[15px] font-semibold text-gray-900">SKU</label>
                <input type="text" id="sku" name="sku" value="{{ old('sku', $product?->sku) }}"
                       class="h-[48px] w-full rounded-[12px] border bg-white px-4 text-[15px] text-gray-700 outline-none @error('sku') border-red-300 focus:border-red-400 focus:ring-2 focus:ring-red-100 @else border-gray-300 focus:border-sky-500 focus:ring-2 focus:ring-sky-200 @enderror">
                @error('sku') <p class="mt-2 text-[14px] text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="barcode" class="mb-2 block text-[15px] font-semibold text-gray-900">Barcode</label>
                <input type="text" id="barcode" name="barcode" value="{{ old('barcode', $product?->barcode) }}"
                       class="h-[48px] w-full rounded-[12px] border border-gray-300 bg-white px-4 text-[15px] text-gray-700 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-200">
            </div>
        </div>
    </div>

    <div class="rounded-[18px] border border-gray-200 p-5">
        <h3 class="mb-4 text-[18px] font-semibold text-gray-900">Pricing & Inventory</h3>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <div>
                <label for="base_price" class="mb-2 block text-[15px] font-semibold text-gray-900">Base Price</label>
                <input type="number" step="0.01" id="base_price" name="base_price" value="{{ old('base_price', $product?->base_price) }}"
                       class="h-[48px] w-full rounded-[12px] border border-gray-300 bg-white px-4 text-[15px] text-gray-700 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-200">
            </div>
            <div>
                <label for="sale_price" class="mb-2 block text-[15px] font-semibold text-gray-900">Sale Price</label>
                <input type="number" step="0.01" id="sale_price" name="sale_price" value="{{ old('sale_price', $product?->sale_price) }}"
                       class="h-[48px] w-full rounded-[12px] border border-gray-300 bg-white px-4 text-[15px] text-gray-700 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-200">
            </div>
            <div>
                <label for="cost_price" class="mb-2 block text-[15px] font-semibold text-gray-900">Cost Price</label>
                <input type="number" step="0.01" id="cost_price" name="cost_price" value="{{ old('cost_price', $product?->cost_price) }}"
                       class="h-[48px] w-full rounded-[12px] border border-gray-300 bg-white px-4 text-[15px] text-gray-700 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-200">
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-4">
            <div>
                <label for="weight" class="mb-2 block text-[15px] font-semibold text-gray-900">Weight</label>
                <input type="number" step="0.01" id="weight" name="weight" value="{{ old('weight', $product?->weight) }}"
                       class="h-[48px] w-full rounded-[12px] border border-gray-300 bg-white px-4 text-[15px] text-gray-700 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-200">
            </div>
            <div>
                <label for="length" class="mb-2 block text-[15px] font-semibold text-gray-900">Length</label>
                <input type="number" step="0.01" id="length" name="length" value="{{ old('length', $product?->length) }}"
                       class="h-[48px] w-full rounded-[12px] border border-gray-300 bg-white px-4 text-[15px] text-gray-700 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-200">
            </div>
            <div>
                <label for="width" class="mb-2 block text-[15px] font-semibold text-gray-900">Width</label>
                <input type="number" step="0.01" id="width" name="width" value="{{ old('width', $product?->width) }}"
                       class="h-[48px] w-full rounded-[12px] border border-gray-300 bg-white px-4 text-[15px] text-gray-700 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-200">
            </div>
            <div>
                <label for="height" class="mb-2 block text-[15px] font-semibold text-gray-900">Height</label>
                <input type="number" step="0.01" id="height" name="height" value="{{ old('height', $product?->height) }}"
                       class="h-[48px] w-full rounded-[12px] border border-gray-300 bg-white px-4 text-[15px] text-gray-700 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-200">
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-3">
            <div>
                <label for="product_type" class="mb-2 block text-[15px] font-semibold text-gray-900">Product Type</label>
                <select id="product_type" name="product_type" class="h-[48px] w-full rounded-[12px] border border-gray-300 bg-white px-4 text-[15px] text-gray-700 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-200">
                    @foreach(['simple', 'variable', 'digital'] as $type)
                        <option value="{{ $type }}" @selected(old('product_type', $product?->product_type ?? 'simple') === $type)>{{ ucfirst($type) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status" class="mb-2 block text-[15px] font-semibold text-gray-900">Status</label>
                <select id="status" name="status" class="h-[48px] w-full rounded-[12px] border border-gray-300 bg-white px-4 text-[15px] text-gray-700 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-200">
                    @foreach(['draft', 'pending', 'approved', 'rejected', 'published', 'unpublished'] as $status)
                        <option value="{{ $status }}" @selected(old('status', $product?->status ?? 'draft') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="published_at" class="mb-2 block text-[15px] font-semibold text-gray-900">Published At</label>
                <input type="datetime-local" id="published_at" name="published_at"
                       value="{{ old('published_at', $product?->published_at?->format('Y-m-d\TH:i')) }}"
                       class="h-[48px] w-full rounded-[12px] border border-gray-300 bg-white px-4 text-[15px] text-gray-700 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-200">
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <label for="min_order_qty" class="mb-2 block text-[15px] font-semibold text-gray-900">Min Order Qty</label>
                <input type="number" min="1" id="min_order_qty" name="min_order_qty" value="{{ old('min_order_qty', $product?->min_order_qty ?? 1) }}"
                       class="h-[48px] w-full rounded-[12px] border border-gray-300 bg-white px-4 text-[15px] text-gray-700 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-200">
            </div>
            <div>
                <label for="max_order_qty" class="mb-2 block text-[15px] font-semibold text-gray-900">Max Order Qty</label>
                <input type="number" min="1" id="max_order_qty" name="max_order_qty" value="{{ old('max_order_qty', $product?->max_order_qty) }}"
                       class="h-[48px] w-full rounded-[12px] border border-gray-300 bg-white px-4 text-[15px] text-gray-700 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-200">
            </div>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-3">
            <label class="flex items-center gap-3">
                <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product?->is_featured ?? false))
                       class="h-4 w-4 rounded border-gray-300 text-sky-600 focus:ring-sky-500">
                <span class="text-[15px] font-medium text-gray-700">Featured Product</span>
            </label>

            <label class="flex items-center gap-3">
                <input type="checkbox" name="is_refundable" value="1" @checked(old('is_refundable', $product?->is_refundable ?? true))
                       class="h-4 w-4 rounded border-gray-300 text-sky-600 focus:ring-sky-500">
                <span class="text-[15px] font-medium text-gray-700">Refundable</span>
            </label>

            <label class="flex items-center gap-3">
                <input type="checkbox" name="track_stock" value="1" @checked(old('track_stock', $product?->track_stock ?? true))
                       class="h-4 w-4 rounded border-gray-300 text-sky-600 focus:ring-sky-500">
                <span class="text-[15px] font-medium text-gray-700">Track Stock</span>
            </label>
        </div>
    </div>

    <div class="rounded-[18px] border border-gray-200 p-5">
        <h3 class="mb-4 text-[18px] font-semibold text-gray-900">Descriptions</h3>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div>
                <label for="short_description" class="mb-2 block text-[15px] font-semibold text-gray-900">Short Description</label>
                <textarea id="short_description" name="short_description" rows="5"
                          class="w-full rounded-[12px] border border-gray-300 bg-white px-4 py-3 text-[15px] text-gray-700 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-200">{{ old('short_description', $product?->short_description) }}</textarea>
            </div>

            <div>
                <label for="description" class="mb-2 block text-[15px] font-semibold text-gray-900">Description</label>
                <textarea id="description" name="description" rows="5"
                          class="w-full rounded-[12px] border border-gray-300 bg-white px-4 py-3 text-[15px] text-gray-700 outline-none focus:border-sky-500 focus:ring-2 focus:ring-sky-200">{{ old('description', $product?->description) }}</textarea>
            </div>
        </div>
    </div>

    <div class="rounded-[18px] border border-gray-200 p-5">
        <h3 class="mb-4 text-[18px] font-semibold text-gray-900">Media</h3>

        <label for="images" class="mb-2 block text-[15px] font-semibold text-gray-900">Upload Images</label>
        <input type="file" id="images" name="images[]" multiple
               class="block w-full rounded-[12px] border border-gray-300 bg-white px-4 py-3 text-[15px] text-gray-700">

        @if($product?->media?->count())
            <div class="mt-4 grid grid-cols-2 gap-4 md:grid-cols-4">
                @foreach($product->media as $media)
                    <div class="rounded-[14px] border border-gray-200 p-3">
                        <img src="{{ asset('storage/' . $media->file_path) }}" alt="Product Image"
                             class="h-28 w-full rounded-lg object-cover">
                        <label class="mt-3 flex items-center gap-2 text-[14px] text-red-600">
                            <input type="checkbox" name="delete_media[]" value="{{ $media->id }}"
                                   class="h-4 w-4 rounded border-gray-300 text-red-600 focus:ring-red-500">
                            Delete
                        </label>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="rounded-[18px] border border-gray-200 p-5">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-[18px] font-semibold text-gray-900">Product Attributes</h3>
            <button type="button" onclick="addProductAttributeRow()"
                    class="inline-flex items-center rounded-[10px] border border-sky-200 bg-sky-50 px-4 py-2 text-[14px] font-medium text-sky-700 hover:bg-sky-100">
                <i class="fa-solid fa-plus mr-2"></i>Add Attribute
            </button>
        </div>

        <div id="product-attributes-wrapper" class="space-y-3">
            @foreach($existingProductAttributes as $index => $item)
                <div class="product-attribute-row grid grid-cols-1 gap-3 rounded-[14px] border border-gray-200 p-4 md:grid-cols-12">
                    <div class="md:col-span-4">
                        <label class="mb-2 block text-[14px] font-medium text-gray-700">Attribute</label>
                        <select name="product_attributes[{{ $index }}][attribute_id]"
                                class="attribute-select h-[46px] w-full rounded-[10px] border border-gray-300 px-3 text-[14px]">
                            <option value="">Select Attribute</option>
                            @foreach($attributeList as $attribute)
                                <option value="{{ $attribute->id }}" @selected(($item['attribute_id'] ?? null) == $attribute->id)>{{ $attribute->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-4">
                        <label class="mb-2 block text-[14px] font-medium text-gray-700">Attribute Value</label>
                        <select name="product_attributes[{{ $index }}][attribute_value_id]"
                                class="attribute-value-select h-[46px] w-full rounded-[10px] border border-gray-300 px-3 text-[14px]"
                                data-selected="{{ $item['attribute_value_id'] ?? '' }}">
                            <option value="">Select Value</option>
                        </select>
                    </div>

                    <div class="md:col-span-3">
                        <label class="mb-2 block text-[14px] font-medium text-gray-700">Custom Text</label>
                        <input type="text" name="product_attributes[{{ $index }}][value_text]" value="{{ $item['value_text'] ?? '' }}"
                               class="h-[46px] w-full rounded-[10px] border border-gray-300 px-3 text-[14px]">
                    </div>

                    <div class="md:col-span-1 md:flex md:items-end">
                        <button type="button" onclick="this.closest('.product-attribute-row').remove()"
                                class="inline-flex h-[46px] w-full items-center justify-center rounded-[10px] border border-red-200 bg-red-50 text-red-700 hover:bg-red-100">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="rounded-[18px] border border-gray-200 p-5">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-[18px] font-semibold text-gray-900">Variants</h3>
            <button type="button" onclick="addVariantRow()"
                    class="inline-flex items-center rounded-[10px] border border-sky-200 bg-sky-50 px-4 py-2 text-[14px] font-medium text-sky-700 hover:bg-sky-100">
                <i class="fa-solid fa-plus mr-2"></i>Add Variant
            </button>
        </div>

        <div id="variants-wrapper" class="space-y-4">
            @foreach($existingVariants as $variantIndex => $variant)
                <div class="variant-row rounded-[16px] border border-gray-200 p-4">
                    <div class="mb-4 flex items-center justify-between">
                        <h4 class="text-[16px] font-semibold text-gray-900">Variant</h4>
                        <button type="button" onclick="this.closest('.variant-row').remove()"
                                class="inline-flex items-center rounded-[10px] border border-red-200 bg-red-50 px-3 py-2 text-[14px] text-red-700 hover:bg-red-100">
                            <i class="fa-solid fa-trash mr-2"></i>Remove
                        </button>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                        <input type="text" name="variants[{{ $variantIndex }}][name]" value="{{ $variant['name'] ?? '' }}" placeholder="Variant Name"
                               class="h-[46px] rounded-[10px] border border-gray-300 px-3 text-[14px]">
                        <input type="text" name="variants[{{ $variantIndex }}][sku]" value="{{ $variant['sku'] ?? '' }}" placeholder="Variant SKU"
                               class="h-[46px] rounded-[10px] border border-gray-300 px-3 text-[14px]">
                        <input type="text" name="variants[{{ $variantIndex }}][barcode]" value="{{ $variant['barcode'] ?? '' }}" placeholder="Barcode"
                               class="h-[46px] rounded-[10px] border border-gray-300 px-3 text-[14px]">
                        <input type="number" step="0.01" name="variants[{{ $variantIndex }}][price]" value="{{ $variant['price'] ?? '' }}" placeholder="Price"
                               class="h-[46px] rounded-[10px] border border-gray-300 px-3 text-[14px]">
                    </div>

                    <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-4">
                        <input type="number" step="0.01" name="variants[{{ $variantIndex }}][sale_price]" value="{{ $variant['sale_price'] ?? '' }}" placeholder="Sale Price"
                               class="h-[46px] rounded-[10px] border border-gray-300 px-3 text-[14px]">
                        <input type="number" step="0.01" name="variants[{{ $variantIndex }}][cost_price]" value="{{ $variant['cost_price'] ?? '' }}" placeholder="Cost Price"
                               class="h-[46px] rounded-[10px] border border-gray-300 px-3 text-[14px]">
                        <input type="number" step="0.01" name="variants[{{ $variantIndex }}][weight]" value="{{ $variant['weight'] ?? '' }}" placeholder="Weight"
                               class="h-[46px] rounded-[10px] border border-gray-300 px-3 text-[14px]">
                        <input type="number" name="variants[{{ $variantIndex }}][stock_qty]" value="{{ $variant['stock_qty'] ?? 0 }}" placeholder="Stock Qty"
                               class="h-[46px] rounded-[10px] border border-gray-300 px-3 text-[14px]">
                    </div>

                    <div class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-3">
                        <label class="flex items-center gap-2 text-[14px]">
                            <input type="checkbox" name="variants[{{ $variantIndex }}][track_stock]" value="1" @checked(!empty($variant['track_stock']))
                                   class="h-4 w-4 rounded border-gray-300 text-sky-600">
                            Track Stock
                        </label>
                        <label class="flex items-center gap-2 text-[14px]">
                            <input type="checkbox" name="variants[{{ $variantIndex }}][is_default]" value="1" @checked(!empty($variant['is_default']))
                                   class="h-4 w-4 rounded border-gray-300 text-sky-600">
                            Default Variant
                        </label>
                        <label class="flex items-center gap-2 text-[14px]">
                            <input type="checkbox" name="variants[{{ $variantIndex }}][is_active]" value="1" @checked(!empty($variant['is_active']))
                                   class="h-4 w-4 rounded border-gray-300 text-sky-600">
                            Active
                        </label>
                    </div>

                    <div class="mt-5">
                        <div class="mb-3 flex items-center justify-between">
                            <h5 class="text-[15px] font-semibold text-gray-800">Variant Values</h5>
                            <button type="button" onclick="addVariantValueRow(this, {{ $variantIndex }})"
                                    class="inline-flex items-center rounded-[10px] border border-sky-200 bg-sky-50 px-3 py-2 text-[13px] font-medium text-sky-700 hover:bg-sky-100">
                                <i class="fa-solid fa-plus mr-2"></i>Add Value
                            </button>
                        </div>

                        <div class="variant-values-wrapper space-y-3">
                            @foreach(($variant['values'] ?? []) as $valueIndex => $value)
                                <div class="variant-value-row grid grid-cols-1 gap-3 rounded-[12px] border border-gray-200 p-3 md:grid-cols-12">
                                    <div class="md:col-span-5">
                                        <select name="variants[{{ $variantIndex }}][values][{{ $valueIndex }}][attribute_id]"
                                                class="variant-attribute-select h-[44px] w-full rounded-[10px] border border-gray-300 px-3 text-[14px]">
                                            <option value="">Select Attribute</option>
                                            @foreach(collect($attributeList)->where('is_variation', true) as $attribute)
                                                <option value="{{ $attribute->id }}" @selected(($value['attribute_id'] ?? null) == $attribute->id)>{{ $attribute->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="md:col-span-6">
                                        <select name="variants[{{ $variantIndex }}][values][{{ $valueIndex }}][attribute_value_id]"
                                                class="variant-attribute-value-select h-[44px] w-full rounded-[10px] border border-gray-300 px-3 text-[14px]"
                                                data-selected="{{ $value['attribute_value_id'] ?? '' }}">
                                            <option value="">Select Value</option>
                                        </select>
                                    </div>

                                    <div class="md:col-span-1">
                                        <button type="button" onclick="this.closest('.variant-value-row').remove()"
                                                class="inline-flex h-[44px] w-full items-center justify-center rounded-[10px] border border-red-200 bg-red-50 text-red-700 hover:bg-red-100">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
        <a href="{{ route('admin.products.index') }}"
           class="inline-flex h-[46px] items-center justify-center rounded-[12px] border border-gray-300 bg-white px-5 text-[15px] font-medium text-gray-700 transition hover:bg-gray-50">
            <i class="fa-solid fa-arrow-left mr-2"></i>
            Cancel
        </a>

        <button type="submit"
                class="inline-flex h-[46px] items-center justify-center rounded-[12px] bg-sky-600 px-5 text-[15px] font-semibold text-white shadow-sm transition hover:bg-sky-700">
            <i class="fa-solid fa-floppy-disk mr-2"></i>
            {{ $submitText }}
        </button>
    </div>
</div>

<script>
    const attributesData = @json($attributesJson);

    let productAttributeIndex = {{ count($existingProductAttributes) }};
    let variantIndex = {{ count($existingVariants) }};

    function getAttributeValues(attributeId) {
        const attr = attributesData.find(item => Number(item.id) === Number(attributeId));
        return attr ? attr.values : [];
    }

    function fillAttributeValueOptions(select, attributeId, selectedValue = '') {
        const values = getAttributeValues(attributeId);
        select.innerHTML = '<option value="">Select Value</option>';

        values.forEach(item => {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.value;
            if (String(selectedValue) === String(item.id)) {
                option.selected = true;
            }
            select.appendChild(option);
        });
    }

    function attachAttributeRowEvents(row) {
        const attrSelect = row.querySelector('.attribute-select');
        const valueSelect = row.querySelector('.attribute-value-select');
        const selected = valueSelect.dataset.selected || '';

        fillAttributeValueOptions(valueSelect, attrSelect.value, selected);

        attrSelect.addEventListener('change', function () {
            fillAttributeValueOptions(valueSelect, this.value);
        });
    }

    function addProductAttributeRow() {
        const wrapper = document.getElementById('product-attributes-wrapper');
        const idx = productAttributeIndex++;

        const options = attributesData.map(item =>
            `<option value="${item.id}">${item.name}</option>`
        ).join('');

        const html = `
            <div class="product-attribute-row grid grid-cols-1 gap-3 rounded-[14px] border border-gray-200 p-4 md:grid-cols-12">
                <div class="md:col-span-4">
                    <label class="mb-2 block text-[14px] font-medium text-gray-700">Attribute</label>
                    <select name="product_attributes[${idx}][attribute_id]" class="attribute-select h-[46px] w-full rounded-[10px] border border-gray-300 px-3 text-[14px]">
                        <option value="">Select Attribute</option>
                        ${options}
                    </select>
                </div>

                <div class="md:col-span-4">
                    <label class="mb-2 block text-[14px] font-medium text-gray-700">Attribute Value</label>
                    <select name="product_attributes[${idx}][attribute_value_id]" class="attribute-value-select h-[46px] w-full rounded-[10px] border border-gray-300 px-3 text-[14px]">
                        <option value="">Select Value</option>
                    </select>
                </div>

                <div class="md:col-span-3">
                    <label class="mb-2 block text-[14px] font-medium text-gray-700">Custom Text</label>
                    <input type="text" name="product_attributes[${idx}][value_text]" class="h-[46px] w-full rounded-[10px] border border-gray-300 px-3 text-[14px]">
                </div>

                <div class="md:col-span-1 md:flex md:items-end">
                    <button type="button" onclick="this.closest('.product-attribute-row').remove()"
                            class="inline-flex h-[46px] w-full items-center justify-center rounded-[10px] border border-red-200 bg-red-50 text-red-700 hover:bg-red-100">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        wrapper.insertAdjacentHTML('beforeend', html);
        attachAttributeRowEvents(wrapper.lastElementChild);
    }

    function variationAttributeOptions() {
        return attributesData
            .filter(item => item.is_variation)
            .map(item => `<option value="${item.id}">${item.name}</option>`)
            .join('');
    }

    function addVariantRow() {
        const wrapper = document.getElementById('variants-wrapper');
        const idx = variantIndex++;

        const html = `
            <div class="variant-row rounded-[16px] border border-gray-200 p-4">
                <div class="mb-4 flex items-center justify-between">
                    <h4 class="text-[16px] font-semibold text-gray-900">Variant</h4>
                    <button type="button" onclick="this.closest('.variant-row').remove()"
                            class="inline-flex items-center rounded-[10px] border border-red-200 bg-red-50 px-3 py-2 text-[14px] text-red-700 hover:bg-red-100">
                        <i class="fa-solid fa-trash mr-2"></i>Remove
                    </button>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                    <input type="text" name="variants[${idx}][name]" placeholder="Variant Name" class="h-[46px] rounded-[10px] border border-gray-300 px-3 text-[14px]">
                    <input type="text" name="variants[${idx}][sku]" placeholder="Variant SKU" class="h-[46px] rounded-[10px] border border-gray-300 px-3 text-[14px]">
                    <input type="text" name="variants[${idx}][barcode]" placeholder="Barcode" class="h-[46px] rounded-[10px] border border-gray-300 px-3 text-[14px]">
                    <input type="number" step="0.01" name="variants[${idx}][price]" placeholder="Price" class="h-[46px] rounded-[10px] border border-gray-300 px-3 text-[14px]">
                </div>

                <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-4">
                    <input type="number" step="0.01" name="variants[${idx}][sale_price]" placeholder="Sale Price" class="h-[46px] rounded-[10px] border border-gray-300 px-3 text-[14px]">
                    <input type="number" step="0.01" name="variants[${idx}][cost_price]" placeholder="Cost Price" class="h-[46px] rounded-[10px] border border-gray-300 px-3 text-[14px]">
                    <input type="number" step="0.01" name="variants[${idx}][weight]" placeholder="Weight" class="h-[46px] rounded-[10px] border border-gray-300 px-3 text-[14px]">
                    <input type="number" name="variants[${idx}][stock_qty]" placeholder="Stock Qty" value="0" class="h-[46px] rounded-[10px] border border-gray-300 px-3 text-[14px]">
                </div>

                <div class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-3">
                    <label class="flex items-center gap-2 text-[14px]">
                        <input type="checkbox" name="variants[${idx}][track_stock]" value="1" checked class="h-4 w-4 rounded border-gray-300 text-sky-600">
                        Track Stock
                    </label>
                    <label class="flex items-center gap-2 text-[14px]">
                        <input type="checkbox" name="variants[${idx}][is_default]" value="1" class="h-4 w-4 rounded border-gray-300 text-sky-600">
                        Default Variant
                    </label>
                    <label class="flex items-center gap-2 text-[14px]">
                        <input type="checkbox" name="variants[${idx}][is_active]" value="1" checked class="h-4 w-4 rounded border-gray-300 text-sky-600">
                        Active
                    </label>
                </div>

                <div class="mt-5">
                    <div class="mb-3 flex items-center justify-between">
                        <h5 class="text-[15px] font-semibold text-gray-800">Variant Values</h5>
                        <button type="button" onclick="addVariantValueRow(this, ${idx})"
                                class="inline-flex items-center rounded-[10px] border border-sky-200 bg-sky-50 px-3 py-2 text-[13px] font-medium text-sky-700 hover:bg-sky-100">
                            <i class="fa-solid fa-plus mr-2"></i>Add Value
                        </button>
                    </div>
                    <div class="variant-values-wrapper space-y-3"></div>
                </div>
            </div>
        `;

        wrapper.insertAdjacentHTML('beforeend', html);
    }

    function addVariantValueRow(button, idx) {
        const variantRow = button.closest('.variant-row');
        const wrapper = variantRow.querySelector('.variant-values-wrapper');
        const valueIndex = wrapper.querySelectorAll('.variant-value-row').length;

        const html = `
            <div class="variant-value-row grid grid-cols-1 gap-3 rounded-[12px] border border-gray-200 p-3 md:grid-cols-12">
                <div class="md:col-span-5">
                    <select name="variants[${idx}][values][${valueIndex}][attribute_id]"
                            class="variant-attribute-select h-[44px] w-full rounded-[10px] border border-gray-300 px-3 text-[14px]">
                        <option value="">Select Attribute</option>
                        ${variationAttributeOptions()}
                    </select>
                </div>

                <div class="md:col-span-6">
                    <select name="variants[${idx}][values][${valueIndex}][attribute_value_id]"
                            class="variant-attribute-value-select h-[44px] w-full rounded-[10px] border border-gray-300 px-3 text-[14px]">
                        <option value="">Select Value</option>
                    </select>
                </div>

                <div class="md:col-span-1">
                    <button type="button" onclick="this.closest('.variant-value-row').remove()"
                            class="inline-flex h-[44px] w-full items-center justify-center rounded-[10px] border border-red-200 bg-red-50 text-red-700 hover:bg-red-100">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>
        `;

        wrapper.insertAdjacentHTML('beforeend', html);
        attachVariantValueEvents(wrapper.lastElementChild);
    }

    function attachVariantValueEvents(row) {
        const attrSelect = row.querySelector('.variant-attribute-select');
        const valueSelect = row.querySelector('.variant-attribute-value-select');
        const selected = valueSelect.dataset.selected || '';

        fillAttributeValueOptions(valueSelect, attrSelect.value, selected);

        attrSelect.addEventListener('change', function () {
            fillAttributeValueOptions(valueSelect, this.value);
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.product-attribute-row').forEach(attachAttributeRowEvents);
        document.querySelectorAll('.variant-value-row').forEach(attachVariantValueEvents);
    });
</script>