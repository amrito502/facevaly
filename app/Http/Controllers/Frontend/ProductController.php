<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->string('q')->toString());

        $products = Product::query()
            ->select([
                'id',
                'brand_id',
                'shop_id',
                'name',
                'slug',
                'sku',
                'barcode',
                'regular_price',
                'discounted_price',
                'rating_avg',
                'rating_count',
                'status',
                'created_at',
            ])
            ->with([
                'media' => function ($query) {
                    $query->select(['id', 'product_id', 'file_path', 'is_primary', 'sort_order'])
                        ->orderByDesc('is_primary')
                        ->orderBy('sort_order');
                },
                'brand:id,name',
                'shop:id,shop_name',
            ])
            ->where('status', 'active')
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('slug', 'like', "%{$q}%")
                        ->orWhere('sku', 'like', "%{$q}%")
                        ->orWhere('barcode', 'like', "%{$q}%");
                });
            })
            ->latest('id')
            ->paginate(12)
            ->withQueryString()
            ->through(function ($product) {
                $primaryImage = $product->media->first();

                $basePrice = (float) ($product->regular_price ?? 0);
                $salePrice = $product->discounted_price !== null
                    ? (float) $product->discounted_price
                    : null;

                $discountPercent = null;

                if ($salePrice !== null && $basePrice > 0 && $salePrice < $basePrice) {
                    $discountPercent = round((($basePrice - $salePrice) / $basePrice) * 100);
                }

                return [
                    'id' => (int) $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'sku' => $product->sku,
                    'price' => $basePrice,
                    'sale_price' => $salePrice,
                    'discount_percent' => $discountPercent,
                    'image' => $primaryImage?->file_path
                        ? asset('storage/' . $primaryImage->file_path)
                        : asset('images/no-image.png'),
                    'brand' => $product->brand?->name,
                    'shop' => $product->shop?->shop_name,
                    'rating' => (float) ($product->rating_avg ?? 5),
                    'reviews_count' => (int) ($product->rating_count ?? 0),
                ];
            });

        return Inertia::render('Frontend/Products/Index', [
            'products' => $products,
            'filters' => [
                'q' => $q,
            ],
        ]);
    }

    public function show(Product $product)
    {
        abort_if($product->status !== 'active', 404);

        $product->load([
            'media' => function ($query) {
                $query->select(['id', 'product_id', 'file_path', 'is_primary', 'sort_order'])
                    ->orderByDesc('is_primary')
                    ->orderBy('sort_order');
            },
            'brand:id,name',
            'shop:id,shop_name',
            'category:id,name',
        ]);

        $similarProducts = Product::query()
            ->select([
                'id',
                'category_id',
                'shop_id',
                'name',
                'slug',
                'regular_price',
                'discounted_price',
                'rating_avg',
                'rating_count',
                'status',
            ])
            ->with([
                'media' => function ($query) {
                    $query->select(['id', 'product_id', 'file_path', 'is_primary', 'sort_order'])
                        ->orderByDesc('is_primary')
                        ->orderBy('sort_order');
                },
            ])
            ->where('status', 'active')
            ->where('id', '!=', $product->id)
            ->when($product->category_id, function ($query) use ($product) {
                $query->where('category_id', $product->category_id);
            })
            ->latest('id')
            ->limit(6)
            ->get()
            ->map(function ($item) {
                $primaryImage = $item->media->first();

                $price = (float) ($item->regular_price ?? 0);
                $salePrice = $item->discounted_price !== null
                    ? (float) $item->discounted_price
                    : null;

                $discountPercent = null;
                if ($salePrice !== null && $price > 0 && $salePrice < $price) {
                    $discountPercent = round((($price - $salePrice) / $price) * 100);
                }

                return [
                    'id' => (int) $item->id,
                    'name' => $item->name,
                    'slug' => $item->slug,
                    'price' => $price,
                    'sale_price' => $salePrice,
                    'discount_percent' => $discountPercent,
                    'image' => $primaryImage?->file_path
                        ? asset('storage/' . $primaryImage->file_path)
                        : asset('images/no-image.png'),
                    'rating' => (float) ($item->rating_avg ?? 5),
                    'reviews_count' => (int) ($item->rating_count ?? 0),
                ];
            })
            ->values();

        return Inertia::render('Frontend/Products/Show', [
            'product' => [
                'id' => (int) $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'description' => $product->description,
                'price' => (float) ($product->regular_price ?? 0),
                'sale_price' => $product->discounted_price !== null
                    ? (float) $product->discounted_price
                    : null,
                'brand' => $product->brand?->name,
                'shop' => $product->shop?->shop_name,
                'category' => $product->category?->name,
                'stock_qty' => (int) ($product->stock_qty ?? 0),
                'rating' => (float) ($product->rating_avg ?? 5),
                'reviews_count' => (int) ($product->rating_count ?? 0),
                'images' => $product->media->count()
                    ? $product->media->map(fn ($media) => asset('storage/' . $media->file_path))->values()
                    : [asset('images/no-image.png')],
            ],
            'similarProducts' => $similarProducts,
        ]);
    }
}