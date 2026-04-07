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
        $q = $request->string('q')->toString();

        $products = Product::query()
            ->with([
                'media' => function ($query) {
                    $query->orderByDesc('is_primary')->orderBy('sort_order');
                },
                'brand:id,name',
                'shop:id,shop_name',
            ])
            ->where('status', 'active') // ✅ FIX
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('slug', 'like', "%{$q}%")
                        ->orWhere('sku', 'like', "%{$q}%")
                        ->orWhere('barcode', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString()
            ->through(function ($product) {

                $primaryImage = $product->media->first();

                $basePrice = (float) $product->regular_price; // ✅ FIX
                $salePrice = $product->discounted_price 
                    ? (float) $product->discounted_price 
                    : null; // ✅ FIX

                $discountPercent = null;

                if ($salePrice && $basePrice > 0 && $salePrice < $basePrice) {
                    $discountPercent = round((($basePrice - $salePrice) / $basePrice) * 100);
                }

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'sku' => $product->sku,

                    'price' => $basePrice,
                    'sale_price' => $salePrice,
                    'discount_percent' => $discountPercent,

                    'image' => $primaryImage?->file_path
                        ? asset('storage/' . $primaryImage->file_path)
                        : 'https://via.placeholder.com/400x400?text=No+Image',

                    'brand' => $product->brand?->name,
                    'shop' => $product->shop?->shop_name,

                    'rating' => 5,
                    'reviews_count' => rand(1, 300),
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
        $product->load([
            'media' => function ($query) {
                $query->orderByDesc('is_primary')->orderBy('sort_order');
            },
            'brand:id,name',
            'shop:id,shop_name',
            'category:id,name',
        ]);

        return Inertia::render('Frontend/Products/Show', [
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'description' => $product->description,

                'price' => (float) $product->regular_price, // ✅ FIX
                'sale_price' => $product->discounted_price
                    ? (float) $product->discounted_price
                    : null,

                'brand' => $product->brand?->name,
                'shop' => $product->shop?->shop_name,
                'category' => $product->category?->name,

                'images' => $product->media->map(function ($media) {
                    return asset('storage/' . $media->file_path);
                })->values(),
            ],
        ]);
    }
}