<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PhoneOtp;
use App\Models\User;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ForgotPasswordController extends Controller
{
    public function __construct(private SmsService $smsService)
    {
    }

    public function showForgot()
    {
        $step = 1;

        if (session('forgot.country_code') && session('forgot.phone')) {
            $step = 2;
        }

        if (session('forgot.otp_verified') === true) {
            $step = 3;
        }

        return Inertia::render('Auth/ForgotPassword', [
            'currentStep' => $step,
            'otpExpiresAt' => session('forgot.otp_expires_at'),
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ],
        ]);
    }

    public function sendOtp(Request $request)
    {
        $data = $request->validate([
            'country_code' => ['required', 'string'],
            'phone' => ['required', 'string'],
        ]);

        $phone = preg_replace('/\D+/', '', $data['phone']);

        $user = User::where('country_code', $data['country_code'])
            ->where('phone', $phone)
            ->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'phone' => 'User not found.',
            ]);
        }

        $otp = (string) random_int(100000, 999999);
        $expiresAt = now()->addSeconds(120);

        PhoneOtp::updateOrCreate(
            [
                'country_code' => $data['country_code'],
                'phone' => $phone,
            ],
            [
                'otp' => $otp,
                'expires_at' => $expiresAt,
                'verified_at' => null,
            ]
        );

        // $this->smsService->sendOtp($data['country_code'], $phone, $otp);

        session([
            'forgot.country_code' => $data['country_code'],
            'forgot.phone' => $phone,
            'forgot.otp_verified' => false,
            'forgot.otp_expires_at' => $expiresAt->toIso8601String(),
        ]);

        return redirect('/forgot-password')->with('success', 'OTP sent successfully.');
    }

    public function resendOtp()
    {
        $countryCode = session('forgot.country_code');
        $phone = session('forgot.phone');

        if (! $countryCode || ! $phone) {
            return redirect('/forgot-password')->with('error', 'Please start password reset first.');
        }

        if (session('forgot.otp_verified') === true) {
            return redirect('/forgot-password')->with('error', 'OTP already verified.');
        }

        $otp = (string) random_int(100000, 999999);
        $expiresAt = now()->addSeconds(120);

        PhoneOtp::updateOrCreate(
            [
                'country_code' => $countryCode,
                'phone' => $phone,
            ],
            [
                'otp' => $otp,
                'expires_at' => $expiresAt,
                'verified_at' => null,
            ]
        );

        // $this->smsService->sendOtp($countryCode, $phone, $otp);

        session([
            'forgot.otp_expires_at' => $expiresAt->toIso8601String(),
        ]);

        return redirect('/forgot-password')->with('success', 'OTP resent successfully.');
    }

    public function verifyOtp(Request $request)
    {
        $data = $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $countryCode = session('forgot.country_code');
        $phone = session('forgot.phone');

        if (! $countryCode || ! $phone) {
            throw ValidationException::withMessages([
                'otp' => 'Reset session expired. Please try again.',
            ]);
        }

        $otpRow = PhoneOtp::where('country_code', $countryCode)
            ->where('phone', $phone)
            ->latest()
            ->first();

        if (! $otpRow) {
            throw ValidationException::withMessages([
                'otp' => 'No OTP request found. Please request a new OTP.',
            ]);
        }

        if ($otpRow->verified_at) {
            throw ValidationException::withMessages([
                'otp' => 'This OTP was already used. Please request a new OTP.',
            ]);
        }

        if (now()->gt($otpRow->expires_at)) {
            throw ValidationException::withMessages([
                'otp' => 'OTP expired. Please resend OTP.',
            ]);
        }

        if ($otpRow->otp !== $data['otp']) {
            throw ValidationException::withMessages([
                'otp' => 'Wrong OTP. Please try again.',
            ]);
        }

        $otpRow->update([
            'verified_at' => now(),
        ]);

        session([
            'forgot.otp_verified' => true,
        ]);

        return redirect('/forgot-password')->with('success', 'OTP verified successfully.');
    }

    public function resetPassword(Request $request)
    {
        $data = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (session('forgot.otp_verified') !== true) {
            return redirect('/forgot-password')->with('error', 'Please verify OTP first.');
        }

        $user = User::where('country_code', session('forgot.country_code'))
            ->where('phone', session('forgot.phone'))
            ->firstOrFail();

        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        session()->forget('forgot');

        return redirect('/login')->with('success', 'Password reset successfully.');
    }
}
