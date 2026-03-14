<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureForgotStep
{
    public function handle(Request $request, Closure $next, string $step)
    {
        $countryCode = session('forgot.country_code');
        $phone = session('forgot.phone');
        $otpVerified = session('forgot.otp_verified') === true;

        if (in_array($step, ['otp', 'reset']) && (! $countryCode || ! $phone)) {
            return redirect('/forgot-password')->with('error', 'Please start password reset first.');
        }

        if ($step === 'reset' && ! $otpVerified) {
            return redirect('/forgot-password')->with('error', 'Please verify OTP first.');
        }

        return $next($request);
    }
}
