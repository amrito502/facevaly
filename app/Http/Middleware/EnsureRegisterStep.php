<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureRegisterStep
{
    public function handle(Request $request, Closure $next, string $step)
    {
        $countryCode = session('register.country_code');
        $phone = session('register.phone');
        $otpVerified = session('register.otp_verified') === true;
        $passwordSaved = session()->has('register.password');

        if (in_array($step, ['otp', 'password', 'profile']) && (! $countryCode || ! $phone)) {
            return redirect('/register')->with('error', 'Please start registration first.');
        }

        if (in_array($step, ['password', 'profile']) && ! $otpVerified) {
            return redirect('/register')->with('error', 'Please verify OTP first.');
        }

        if ($step === 'profile' && ! $passwordSaved) {
            return redirect('/register')->with('error', 'Please set password first.');
        }

        return $next($request);
    }
}
