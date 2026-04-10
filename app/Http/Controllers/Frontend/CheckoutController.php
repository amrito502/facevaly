<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Setting;
use App\Models\ShippingRate;
use App\Models\SubOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CheckoutController extends Controller
{
    private function cartQuery(Request $request)
    {
        if (auth()->check()) {
            return Cart::query()->where('user_id', auth()->id());
        }

        return Cart::query()->where('session_id', $request->session()->getId());
    }

    private function getGlobalCommissionRate(): float
    {
        return (float) (Setting::query()->value('default_commission_rate') ?? 5);
    }

    private function resolveCommissionRate(Product $product): float
    {
        if (!is_null($product->commission_rate)) {
            return (float) $product->commission_rate;
        }

        if (!is_null($product->category?->commission_rate)) {
            return (float) $product->category->commission_rate;
        }

        if (!is_null($product->shop?->commission_rate)) {
            return (float) $product->shop->commission_rate;
        }

        return $this->getGlobalCommissionRate();
    }

    private function cartItemPayload($cart, Product $product): array
    {
        $image = optional(
            $product->media->sortByDesc('is_primary')->sortBy('sort_order')->first()
        )->file_path;

        $sellerPrice = (float) ($product->seller_price ?? 0);
        $salePrice = (float) ($product->sale_price ?? 0);
        $qty = (int) $cart->quantity;

        $lineSellerTotal = $sellerPrice * $qty;
        $lineSaleTotal = $salePrice * $qty;
        $markupAmount = $lineSaleTotal - $lineSellerTotal;

        $commissionRate = $this->resolveCommissionRate($product);
        $commissionAmount = ($lineSellerTotal * $commissionRate) / 100;

        $sellerPayable = $lineSellerTotal - $commissionAmount;
        $adminEarning = $markupAmount + $commissionAmount;

        return [
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'shop_id' => $product->shop?->id,
            'seller_id' => $product->shop?->seller_id,
            'shop_name' => $product->shop?->shop_name ?? 'Top Fair',
            'name' => $product->name,
            'slug' => $product->slug,
            'image' => $image ? asset('storage/' . $image) : asset('images/no-image.png'),
            'seller_price' => $sellerPrice,
            'sale_price' => $salePrice,
            'quantity' => $qty,
            'line_seller_total' => $lineSellerTotal,
            'line_sale_total' => $lineSaleTotal,
            'markup_amount' => $markupAmount,
            'commission_rate' => $commissionRate,
            'commission_amount' => $commissionAmount,
            'seller_payable' => $sellerPayable,
            'admin_earning' => $adminEarning,
        ];
    }

    private function getCartItems(Request $request)
    {
        return $this->cartQuery($request)
            ->with([
                'product:id,shop_id,category_id,name,slug,seller_price,sale_price,commission_rate,status,stock_qty',
                'product.shop:id,seller_id,shop_name,commission_rate',
                'product.category:id,name,commission_rate',
                'product.media:id,product_id,file_path,is_primary,sort_order',
            ])
            ->get()
            ->map(function ($cart) {
                $product = $cart->product;

                if (!$product || $product->status !== 'active') {
                    return null;
                }

                return $this->cartItemPayload($cart, $product);
            })
            ->filter()
            ->values();
    }

    private function calculateSummary(Request $request, ?string $couponCode = null, ?int $shippingRateId = null): array
    {
        $cartItems = $this->getCartItems($request);

        $subtotal = (float) $cartItems->sum('line_sale_total');
        $discount = 0;
        $coupon = null;

        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();

            if ($coupon && $coupon->isValidForAmount($subtotal)) {
                $discount = $coupon->calculateDiscount($subtotal);
            }
        }

        $shipping = 0;
        $shippingRate = null;

        if ($shippingRateId) {
            $shippingRate = ShippingRate::where('id', $shippingRateId)
                ->where('status', 'active')
                ->first();

            $shipping = $shippingRate ? (float) $shippingRate->shipping_cost : 0;
        }

        $tax = 0;
        $total = max(0, $subtotal - $discount + $shipping + $tax);

        return [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'shipping_cost' => $shipping,
            'total' => $total,
            'coupon' => $coupon,
            'shipping_rate' => $shippingRate,
        ];
    }

    public function index(Request $request)
    {
        $shippingRates = ShippingRate::where('status', 'active')->get(['id', 'location', 'shipping_cost']);
        $defaultShipping = $shippingRates->first();

        $summary = $this->calculateSummary($request, null, $defaultShipping?->id);

        $defaultAddress = auth()->check()
            ? Address::where('user_id', auth()->id())->where('is_default', true)->first()
            : null;

        return Inertia::render('Frontend/Checkout/Index', [
            'cartItems' => $summary['cartItems'],
            'shippingRates' => $shippingRates,
            'summary' => [
                'subtotal' => $summary['subtotal'],
                'discount' => $summary['discount'],
                'shipping_cost' => $summary['shipping_cost'],
                'tax' => $summary['tax'],
                'total' => $summary['total'],
            ],
            'defaultAddress' => $defaultAddress,
        ]);
    }

    public function applyCoupon(Request $request)
    {
        $validated = $request->validate([
            'coupon_code' => ['required', 'string'],
            'shipping_rate_id' => ['nullable', 'integer'],
        ]);

        $summary = $this->calculateSummary(
            $request,
            $validated['coupon_code'],
            $validated['shipping_rate_id'] ?? null
        );

        if (!$summary['coupon']) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon code.',
            ], 422);
        }

        if (!$summary['coupon']->isValidForAmount($summary['subtotal'])) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon is not valid for this cart.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Coupon applied successfully.',
            'discount' => $summary['discount'],
            'subtotal' => $summary['subtotal'],
            'shipping_cost' => $summary['shipping_cost'],
            'tax' => $summary['tax'],
            'total' => $summary['total'],
            'coupon_code' => $summary['coupon']->code,
            'coupon_id' => $summary['coupon']->id,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'full_address' => ['required', 'string'],
            'payment_method' => ['required', 'in:cod,sslcommerz'],
            'shipping_rate_id' => ['required', 'integer', 'exists:shipping_rates,id'],
            'coupon_code' => ['nullable', 'string'],
            'save_address' => ['nullable', 'boolean'],
        ]);

        $summary = $this->calculateSummary(
            $request,
            $validated['coupon_code'] ?? null,
            $validated['shipping_rate_id']
        );

        if ($summary['cartItems']->isEmpty()) {
            return back()->withErrors([
                'cart' => 'Cart is empty.',
            ]);
        }

        $order = DB::transaction(function () use ($request, $validated, $summary) {
            foreach ($summary['cartItems'] as $item) {
                $product = Product::find($item['product_id']);

                if (!$product || $product->status !== 'active') {
                    abort(422, 'A product is no longer available.');
                }

                if (!is_null($product->stock_qty) && $product->stock_qty < $item['quantity']) {
                    abort(422, 'Stock not available for ' . $product->name);
                }
            }

            $coupon = null;
            if (!empty($validated['coupon_code'])) {
                $coupon = Coupon::where('code', $validated['coupon_code'])->first();
            }

            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => 'ORD-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(5)),
                'coupon_id' => $coupon?->id,
                'subtotal' => $summary['subtotal'],
                'discount' => $summary['discount'],
                'tax' => $summary['tax'],
                'shipping_rate_id' => $validated['shipping_rate_id'],
                'shipping_cost' => $summary['shipping_cost'],
                'total' => $summary['total'],
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'full_address' => $validated['full_address'],
                'payment_method' => $validated['payment_method'],
                'payment_status' => $validated['payment_method'] === 'cod' ? 'unpaid' : 'paid',
                'status' => 'pending',
            ]);

            $grouped = $summary['cartItems']->groupBy('shop_id');

            foreach ($grouped as $shopId => $items) {
                $sellerId = $items->first()['seller_id'] ?? null;

                $subSubtotal = (float) $items->sum('line_sale_total');
                $subCommissionAmount = (float) $items->sum('commission_amount');
                $subMarkupAmount = (float) $items->sum('markup_amount');
                $subSellerPayable = (float) $items->sum('seller_payable');
                $subAdminEarning = (float) $items->sum('admin_earning');

                $avgCommissionRate = $subSubtotal > 0
                    ? round(($subCommissionAmount / max(1, $items->sum('line_seller_total'))) * 100, 2)
                    : 0;

                $subOrder = SubOrder::create([
                    'order_id' => $order->id,
                    'shop_id' => $shopId,
                    'seller_id' => $sellerId,
                    'sub_order_number' => 'SORD-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4)),
                    'subtotal' => $subSubtotal,
                    'discount' => 0,
                    'tax' => 0,
                    'shipping_cost' => 0,
                    'total' => $subSubtotal,
                    'commission_rate' => $avgCommissionRate,
                    'commission_amount' => $subCommissionAmount,
                    'markup_amount' => $subMarkupAmount,
                    'gateway_charge' => 0,
                    'seller_payable' => $subSellerPayable,
                    'admin_earning' => $subAdminEarning,
                    'status' => 'pending',
                ]);

                foreach ($items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'sub_order_id' => $subOrder->id,
                        'product_id' => $item['product_id'],
                        'shop_id' => $item['shop_id'],
                        'seller_id' => $item['seller_id'],
                        'seller_price' => $item['seller_price'],
                        'sale_price' => $item['sale_price'],
                        'quantity' => $item['quantity'],
                        'line_seller_total' => $item['line_seller_total'],
                        'line_sale_total' => $item['line_sale_total'],
                        'markup_amount' => $item['markup_amount'],
                        'commission_rate' => $item['commission_rate'],
                        'commission_amount' => $item['commission_amount'],
                        'seller_payable' => $item['seller_payable'],
                        'admin_earning' => $item['admin_earning'],
                        'options' => null,
                    ]);

                    Product::where('id', $item['product_id'])->decrement('stock_qty', $item['quantity']);
                }
            }

            if ($coupon) {
                $coupon->increment('used_count');
            }

            if (auth()->check() && ($validated['save_address'] ?? false)) {
                Address::updateOrCreate(
                    [
                        'user_id' => auth()->id(),
                        'phone' => $validated['phone'],
                    ],
                    [
                        'name' => $validated['name'],
                        'full_address' => $validated['full_address'],
                        'is_default' => true,
                    ]
                );
            }

            $this->cartQuery($request)->delete();

            return $order;
        });

        return redirect()->route('order.success', $order->id);
    }

    public function success(Order $order)
    {
        return Inertia::render('Frontend/Checkout/Success', [
            'order' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
            ],
        ]);
    }
}