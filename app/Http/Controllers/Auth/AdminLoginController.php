<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.admin-login');
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

        if (! auth()->user()->hasRole('admin')) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'email' => 'You are not authorized as admin.',
            ])->withInput();
        }

        return redirect()->route('admin.dashboard');
    }
}
