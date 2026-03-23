<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.seller-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($request->only('email', 'password'))) {
            return back()->withErrors([
                'email' => 'Invalid credentials.',
            ])->withInput();
        }

        $request->session()->regenerate();

        if (! auth()->user()->hasRole('seller')) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'You are not authorized as seller.',
            ])->withInput();
        }

        return redirect()->route('seller.dashboard');
    }
}
