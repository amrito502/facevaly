<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductMedia;
use App\Models\ProductVariant;
use App\Models\ProductVariantValue;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q;

        $products = Product::with(['shop', 'category', 'brand', 'media'])
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('slug', 'like', "%{$q}%")
                        ->orWhere('sku', 'like', "%{$q}%")
                        ->orWhere('barcode', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $shops = Shop::orderBy('shop_name')->get();
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $attributes = Attribute::with('values')->orderBy('name')->get();

        return view('admin.products.create', compact('shops', 'categories', 'brands', 'attributes'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateProduct($request);

        DB::transaction(function () use ($request, $validated) {
            $product = Product::create($this->productPayload($request, $validated));

            $this->storeProductMedia($request, $product);
            $this->syncProductAttributes($request, $product);
            $this->syncVariants($request, $product);
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $product->load([
            'media',
            'attributes.attribute',
            'attributes.attributeValue',
            'variants.values.attribute',
            'variants.values.attributeValue',
        ]);

        $shops = Shop::orderBy('shop_name')->get();
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $attributes = Attribute::with('values')->orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'shops', 'categories', 'brands', 'attributes'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $this->validateProduct($request, $product);

        DB::transaction(function () use ($request, $validated, $product) {
            $product->update($this->productPayload($request, $validated));

            $this->deleteRemovedMedia($request, $product);
            $this->storeProductMedia($request, $product);

            ProductAttribute::where('product_id', $product->id)->delete();
            $this->syncProductAttributes($request, $product);

            $this->replaceVariants($request, $product);
        });

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        DB::transaction(function () use ($product) {
            foreach ($product->media as $media) {
                if ($media->file_path && Storage::disk('public')->exists($media->file_path)) {
                    Storage::disk('public')->delete($media->file_path);
                }
            }

            $product->delete();
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    protected function validateProduct(Request $request, ?Product $product = null): array
    {
        return $request->validate([
            'shop_id' => ['required', 'exists:shops,id'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('products', 'slug')->ignore($product?->id),
            ],
            'sku' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'sku')->ignore($product?->id),
            ],
            'barcode' => ['nullable', 'string', 'max:255'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'weight' => ['nullable', 'numeric', 'min:0'],
            'length' => ['nullable', 'numeric', 'min:0'],
            'width' => ['nullable', 'numeric', 'min:0'],
            'height' => ['nullable', 'numeric', 'min:0'],
            'product_type' => ['required', Rule::in(['simple', 'variable', 'digital'])],
            'status' => ['required', Rule::in(['draft', 'pending', 'approved', 'rejected', 'published', 'unpublished'])],
            'min_order_qty' => ['nullable', 'integer', 'min:1'],
            'max_order_qty' => ['nullable', 'integer', 'min:1'],
            'published_at' => ['nullable', 'date'],
            'images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'delete_media' => ['nullable', 'array'],
            'delete_media.*' => ['nullable', 'integer', 'exists:product_media,id'],

            'product_attributes' => ['nullable', 'array'],
            'product_attributes.*.attribute_id' => ['nullable', 'exists:attributes,id'],
            'product_attributes.*.attribute_value_id' => ['nullable', 'exists:attribute_values,id'],
            'product_attributes.*.value_text' => ['nullable', 'string', 'max:255'],

            'variants' => ['nullable', 'array'],
            'variants.*.name' => ['nullable', 'string', 'max:255'],
            'variants.*.sku' => ['nullable', 'string', 'max:255'],
            'variants.*.barcode' => ['nullable', 'string', 'max:255'],
            'variants.*.price' => ['nullable', 'numeric', 'min:0'],
            'variants.*.sale_price' => ['nullable', 'numeric', 'min:0'],
            'variants.*.cost_price' => ['nullable', 'numeric', 'min:0'],
            'variants.*.weight' => ['nullable', 'numeric', 'min:0'],
            'variants.*.stock_qty' => ['nullable', 'integer', 'min:0'],
            'variants.*.track_stock' => ['nullable'],
            'variants.*.is_default' => ['nullable'],
            'variants.*.is_active' => ['nullable'],
            'variants.*.values' => ['nullable', 'array'],
            'variants.*.values.*.attribute_id' => ['nullable', 'exists:attributes,id'],
            'variants.*.values.*.attribute_value_id' => ['nullable', 'exists:attribute_values,id'],
        ]);
    }

    protected function productPayload(Request $request, array $validated): array
    {
        return [
            'shop_id' => $validated['shop_id'],
            'category_id' => $validated['category_id'] ?? null,
            'brand_id' => $validated['brand_id'] ?? null,
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?: Str::slug($validated['name']),
            'sku' => $validated['sku'],
            'barcode' => $validated['barcode'] ?? null,
            'short_description' => $validated['short_description'] ?? null,
            'description' => $validated['description'] ?? null,
            'base_price' => $validated['base_price'],
            'sale_price' => $validated['sale_price'] ?? null,
            'cost_price' => $validated['cost_price'] ?? null,
            'weight' => $validated['weight'] ?? null,
            'length' => $validated['length'] ?? null,
            'width' => $validated['width'] ?? null,
            'height' => $validated['height'] ?? null,
            'product_type' => $validated['product_type'],
            'status' => $validated['status'],
            'is_featured' => $request->boolean('is_featured'),
            'is_refundable' => $request->boolean('is_refundable', true),
            'track_stock' => $request->boolean('track_stock', true),
            'min_order_qty' => $validated['min_order_qty'] ?? 1,
            'max_order_qty' => $validated['max_order_qty'] ?? null,
            'published_at' => $validated['published_at'] ?? null,
        ];
    }

    protected function storeProductMedia(Request $request, Product $product): void
    {
        if (!$request->hasFile('images')) {
            return;
        }

        $lastSort = (int) $product->media()->max('sort_order');

        foreach ($request->file('images') as $index => $image) {
            ProductMedia::create([
                'product_id' => $product->id,
                'type' => 'image',
                'file_path' => $image->store('products', 'public'),
                'is_primary' => $product->media()->count() === 0 && $index === 0,
                'sort_order' => $lastSort + $index + 1,
            ]);
        }
    }

    protected function deleteRemovedMedia(Request $request, Product $product): void
    {
        $deleteIds = collect($request->input('delete_media', []))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->values();

        if ($deleteIds->isEmpty()) {
            return;
        }

        $mediaItems = $product->media()->whereIn('id', $deleteIds)->get();

        foreach ($mediaItems as $media) {
            if ($media->file_path && Storage::disk('public')->exists($media->file_path)) {
                Storage::disk('public')->delete($media->file_path);
            }
            $media->delete();
        }

        $firstMedia = $product->media()->orderBy('sort_order')->first();
        if ($firstMedia && !$product->media()->where('is_primary', true)->exists()) {
            $firstMedia->update(['is_primary' => true]);
        }
    }

    protected function syncProductAttributes(Request $request, Product $product): void
    {
        $rows = $request->input('product_attributes', []);

        foreach ($rows as $row) {
            $attributeId = $row['attribute_id'] ?? null;
            $attributeValueId = $row['attribute_value_id'] ?? null;
            $valueText = $row['value_text'] ?? null;

            if (!$attributeId) {
                continue;
            }

            if (!$attributeValueId && blank($valueText)) {
                continue;
            }

            ProductAttribute::create([
                'product_id' => $product->id,
                'attribute_id' => $attributeId,
                'attribute_value_id' => $attributeValueId ?: null,
                'value_text' => filled($valueText) ? $valueText : null,
            ]);
        }
    }

    protected function syncVariants(Request $request, Product $product): void
    {
        $variants = $request->input('variants', []);

        foreach ($variants as $variantRow) {
            if (blank($variantRow['name'] ?? null) || blank($variantRow['sku'] ?? null)) {
                continue;
            }

            $variant = ProductVariant::create([
                'product_id' => $product->id,
                'name' => $variantRow['name'],
                'sku' => $variantRow['sku'],
                'barcode' => $variantRow['barcode'] ?? null,
                'price' => $variantRow['price'] ?? 0,
                'sale_price' => $variantRow['sale_price'] ?? null,
                'cost_price' => $variantRow['cost_price'] ?? null,
                'weight' => $variantRow['weight'] ?? null,
                'stock_qty' => $variantRow['stock_qty'] ?? 0,
                'track_stock' => !empty($variantRow['track_stock']),
                'is_default' => !empty($variantRow['is_default']),
                'is_active' => !empty($variantRow['is_active']),
            ]);

            foreach (($variantRow['values'] ?? []) as $valueRow) {
                if (empty($valueRow['attribute_id']) || empty($valueRow['attribute_value_id'])) {
                    continue;
                }

                ProductVariantValue::create([
                    'product_variant_id' => $variant->id,
                    'attribute_id' => $valueRow['attribute_id'],
                    'attribute_value_id' => $valueRow['attribute_value_id'],
                ]);
            }
        }
    }

    protected function replaceVariants(Request $request, Product $product): void
    {
        foreach ($product->variants as $variant) {
            $variant->values()->delete();
        }

        $product->variants()->delete();

        $this->syncVariants($request, $product);
    }
}