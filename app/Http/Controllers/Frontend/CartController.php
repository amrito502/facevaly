<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    private function cartQuery(Request $request)
    {
        if (auth()->check()) {
            return Cart::query()->where('user_id', auth()->id());
        }

        return Cart::query()->where('session_id', $request->session()->getId());
    }

    private function ownsCart(Request $request, Cart $cart): bool
    {
        if (auth()->check()) {
            return (int) $cart->user_id === (int) auth()->id();
        }

        return $cart->session_id === $request->session()->getId();
    }

    private function getCartItems(Request $request)
    {
        return $this->cartQuery($request)
            ->select(['id', 'user_id', 'session_id', 'product_id', 'quantity', 'created_at'])
            ->with([
                'product:id,shop_id,name,slug,regular_price,discounted_price,status',
                'product.media' => function ($query) {
                    $query->select(['id', 'product_id', 'file_path', 'is_primary', 'sort_order'])
                        ->orderByDesc('is_primary')
                        ->orderBy('sort_order');
                },
                'product.shop:id,shop_name',
            ])
            ->latest('id')
            ->get()
            ->map(function ($cart) {
                $product = $cart->product;

                if (!$product || $product->status !== 'active') {
                    return null;
                }

                $primaryImage = $product->media->first();

                $price = (float) ($product->regular_price ?? 0);
                $salePrice = $product->discounted_price !== null
                    ? (float) $product->discounted_price
                    : null;

                $finalPrice = $salePrice ?? $price;
                $quantity = (int) $cart->quantity;

                return [
                    'id' => (int) $cart->id,
                    'product_id' => (int) $product->id,
                    'shop_name' => $product->shop?->shop_name ?? 'Top Fair',
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'image' => $primaryImage?->file_path
                        ? asset('storage/' . $primaryImage->file_path)
                        : asset('images/no-image.png'),
                    'price' => $price,
                    'sale_price' => $salePrice,
                    'final_price' => $finalPrice,
                    'quantity' => $quantity,
                    'line_total' => $finalPrice * $quantity,
                ];
            })
            ->filter()
            ->values();
    }

    private function cartPayload(Request $request): array
    {
        $items = $this->getCartItems($request);

        return [
            'cartItems' => $items,
            'cartCount' => $items->sum('quantity'),
            'cartSubtotal' => (float) $items->sum('line_total'),
        ];
    }

    private function successResponse(Request $request, string $message, int $status = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            ...$this->cartPayload($request),
        ], $status);
    }

    private function errorResponse(string $message, array $errors = [], int $status = 422)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }

    public function index(Request $request)
    {
        return Inertia::render('Frontend/Cart/Index', [
            'cartItems' => $this->getCartItems($request),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $product = Product::query()
            ->select(['id', 'status', 'stock_qty'])
            ->findOrFail($validated['product_id']);

        if ($product->status !== 'active') {
            return $this->errorResponse('Product is not available.');
        }

        $quantity = (int) ($validated['quantity'] ?? 1);

        if ($product->stock_qty !== null && $product->stock_qty < $quantity) {
            return $this->errorResponse('Requested quantity is not available in stock.', [
                'quantity' => ['Requested quantity is not available in stock.'],
            ]);
        }

        $existing = $this->cartQuery($request)
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($existing) {
            $newQty = (int) $existing->quantity + $quantity;

            if ($product->stock_qty !== null && $newQty > $product->stock_qty) {
                return $this->errorResponse('Stock limit exceeded.', [
                    'quantity' => ['Stock limit exceeded.'],
                ]);
            }

            $existing->update([
                'quantity' => $newQty,
            ]);
        } else {
            Cart::create([
                'user_id' => auth()->check() ? auth()->id() : null,
                'session_id' => auth()->check() ? null : $request->session()->getId(),
                'product_id' => $validated['product_id'],
                'quantity' => $quantity,
            ]);
        }

        return $this->successResponse($request, 'Product added to cart');
    }

    public function update(Request $request, Cart $cart)
    {
        if (!$this->ownsCart($request, $cart)) {
            return $this->errorResponse('Unauthorized cart access.', [], 403);
        }

        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $product = Product::query()
            ->select(['id', 'status', 'stock_qty'])
            ->find($cart->product_id);

        if (!$product || $product->status !== 'active') {
            return $this->errorResponse('This product is no longer available.', [
                'quantity' => ['This product is no longer available.'],
            ]);
        }

        if ($product->stock_qty !== null && (int) $validated['quantity'] > $product->stock_qty) {
            return $this->errorResponse('Requested quantity exceeds available stock.', [
                'quantity' => ['Requested quantity exceeds available stock.'],
            ]);
        }

        $cart->update([
            'quantity' => (int) $validated['quantity'],
        ]);

        return $this->successResponse($request, 'Cart quantity updated');
    }

    public function destroy(Request $request, Cart $cart)
    {
        if (!$this->ownsCart($request, $cart)) {
            return $this->errorResponse('Unauthorized cart access.', [], 403);
        }

        $cart->delete();

        return $this->successResponse($request, 'Item removed from cart');
    }

    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'cart_ids' => ['required', 'array', 'min:1'],
            'cart_ids.*' => ['integer', 'exists:carts,id'],
        ]);

        $this->cartQuery($request)
            ->whereIn('id', $validated['cart_ids'])
            ->delete();

        return $this->successResponse($request, 'Selected items removed');
    }
}