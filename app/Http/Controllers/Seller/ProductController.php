<?php

namespace App\Http\Controllers\Seller;

use App\Models\Brand;
use App\Models\Shop;
use App\Models\Unit;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\StoreProductRequest;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{

     public function index(Request $request): Response
    {
        $seller = auth()->user();

        $shop = Shop::where('seller_id', $seller->id)->firstOrFail();

        $products = Product::with([
                'category:id,name',
                'brand:id,name',
                'media:id,product_id,file_path,role,is_primary',
            ])
            ->where('shop_id', $shop->id)
            ->latest()
            ->paginate(10)
            ->through(function ($product) {
                $thumbnail = $product->media
                    ->where('role', 'thumbnail')
                    ->first()?->file_path;

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'sku' => $product->sku,
                    'product_type' => $product->product_type,
                    'regular_price' => $product->regular_price,
                    'discounted_price' => $product->discounted_price,
                    'stock_qty' => $product->stock_qty,
                    'status' => $product->status,
                    'is_featured' => $product->is_featured,
                    'published_at' => optional($product->published_at)?->format('d M Y'),
                    'category' => $product->category?->name,
                    'brand' => $product->brand?->name,
                    'thumbnail' => $thumbnail ? asset('storage/' . $thumbnail) : null,
                ];
            });

        return Inertia::render('Seller/Products/Index', [
            'products' => $products,
        ]);
    }


    public function create(): Response
    {
        $seller = auth()->user();

        $shop = Shop::where('seller_id', $seller->id)->first();

        return Inertia::render('Seller/Products/Create', [
            'categories' => Category::where('is_active', true)
                ->orderBy('sort_order')
                ->get(['id', 'name', 'parent_id']),

            'brands' => Brand::where('is_active', true)
                ->when($shop, function ($query) use ($shop) {
                    $query->where(function ($q) use ($shop) {
                        $q->whereNull('shop_id')
                          ->orWhere('shop_id', $shop->id);
                    });
                })
                ->orderBy('name')
                ->get(['id', 'name']),

            'units' => Unit::where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name', 'short_name']),

            'shop' => $shop,
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        $seller = auth()->user();

        $shop = Shop::where('seller_id', $seller->id)->firstOrFail();

        DB::transaction(function () use ($request, $shop) {
            $data = $request->validated();

            $product = Product::create([
                'shop_id' => $shop->id,
                'category_id' => $data['category_id'],
                'brand_id' => $data['brand_id'] ?? null,
                'unit_id' => $data['unit_id'] ?? null,

                'name' => $data['name'],
                'slug' => Str::slug($data['name']) . '-' . Str::lower(Str::random(6)),
                'sku' => $data['sku'] ?? null,
                'barcode' => $data['barcode'] ?? null,

                'product_type' => $data['product_type'],

                'description' => $data['description'],
                'specification' => $data['specification'] ?? null,

                'purchase_price' => $data['purchase_price'] ?? 0,
                'regular_price' => $data['regular_price'] ?? null,
                'discounted_price' => $data['discounted_price'] ?? null,

                'is_retail' => (bool) ($data['is_retail'] ?? false),
                'is_wholesale' => (bool) ($data['is_wholesale'] ?? false),
                'wholesale_price' => $data['wholesale_price'] ?? null,
                'wholesale_min_qty' => $data['wholesale_min_qty'] ?? null,

                'stock_qty' => $data['stock_qty'] ?? 0,
                'weight_kg' => $data['weight_kg'] ?? null,

                'warranty_type' => $data['warranty_type'],
                'warranty_policy' => $data['warranty_policy'] ?? null,

                'tax_amount' => 0,
                'tax_type' => 'percent',
                'status' => 'active',
                'published_at' => now(),
            ]);

            if ($request->hasFile('thumbnail')) {
                $path = $request->file('thumbnail')->store('products/thumbnail', 'public');

                $product->media()->create([
                    'type' => 'image',
                    'role' => 'thumbnail',
                    'file_path' => $path,
                    'sort_order' => 0,
                    'is_primary' => true,
                ]);
            }

            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $index => $image) {
                    $path = $image->store('products/gallery', 'public');

                    $product->media()->create([
                        'type' => 'image',
                        'role' => 'gallery',
                        'file_path' => $path,
                        'sort_order' => $index + 1,
                        'is_primary' => false,
                    ]);
                }
            }

            if (($data['warranty_type'] ?? null) === 'seller_warranty') {
                if (!empty($data['replacement_warranty_enabled'])) {
                    $product->warranties()->create([
                        'type' => 'replacement',
                        'duration' => $data['replacement_duration'] ?? null,
                        'duration_unit' => $data['replacement_duration_unit'] ?? 'day',
                    ]);
                }

                if (!empty($data['service_warranty_enabled'])) {
                    $product->warranties()->create([
                        'type' => 'service',
                        'duration' => $data['service_duration'] ?? null,
                        'duration_unit' => $data['service_duration_unit'] ?? 'day',
                    ]);
                }
            }
        });

        return redirect()
            ->back()
            ->with('success', 'Product created successfully.');
    }
}