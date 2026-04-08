<?php

namespace App\Http\Middleware;

use App\Models\Cart;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function share(Request $request): array
    {
        $cartCount = 0;

        if (auth()->check()) {
            $cartCount = Cart::query()
                ->where('user_id', auth()->id())
                ->sum('quantity');
        } else {
            $cartCount = Cart::query()
                ->where('session_id', $request->session()->getId())
                ->sum('quantity');
        }

        return array_merge(parent::share($request), [
            'cartCount' => $cartCount,
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
        ]);
    }
}