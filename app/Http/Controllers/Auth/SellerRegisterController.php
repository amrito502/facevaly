<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PhoneOtp;
use App\Models\User;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class SellerRegisterController extends Controller
{
    public function __construct(private SmsService $smsService)
    {
    }

    public function showRegister()
    {
        $step = 1;

        if (
            session()->has('seller_register.country_code') &&
            session()->has('seller_register.phone')
        ) {
            $step = 2;
        }

        if (session('seller_register.otp_verified') === true) {
            $step = 3;
        }

        if (
            session('seller_register.otp_verified') === true &&
            session()->has('seller_register.password')
        ) {
            $step = 4;
        }

        return Inertia::render('Auth/SellerRegister', [
            'currentStep' => $step,
            'otpExpiresAt' => session('seller_register.otp_expires_at'),
            'sellerSession' => [
                'country_code' => session('seller_register.country_code', '+880'),
                'phone' => session('seller_register.phone', ''),
                'otp_verified' => session('seller_register.otp_verified', false),
                'password_saved' => session()->has('seller_register.password'),
                'referral_code' => session('seller_register.referral_code', ''),
                'shop_name' => session('seller_register.shop_name', ''),
                'owner_name' => session('seller_register.owner_name', ''),
                'owner_phone' => session('seller_register.owner_phone', ''),
                'whatsapp_number' => session('seller_register.whatsapp_number', ''),
            ],
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ],
        ]);
    }

    public function step1(Request $request)
    {
        $data = $request->validate([
            'country_code' => ['required', 'string', 'max:10'],
            'phone' => ['required', 'string', 'max:20'],
        ]);

        $countryCode = trim($data['country_code']);
        $phone = preg_replace('/\D+/', '', $data['phone']);

        if ($phone === '') {
            throw ValidationException::withMessages([
                'phone' => 'Phone number is required.',
            ]);
        }

        $exists = User::where('country_code', $countryCode)
            ->where('phone', $phone)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'phone' => 'Phone already registered.',
            ]);
        }

        $otp = (string) random_int(100000, 999999);
        $expiresAt = now()->addMinutes(2);

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
            'seller_register.country_code' => $countryCode,
            'seller_register.phone' => $phone,
            'seller_register.otp_verified' => false,
            'seller_register.otp_expires_at' => $expiresAt->toIso8601String(),
        ]);

        session()->forget([
            'seller_register.password',
            'seller_register.referral_code',
            'seller_register.shop_name',
            'seller_register.owner_name',
            'seller_register.owner_phone',
            'seller_register.whatsapp_number',
        ]);

        return redirect()
            ->route('seller.register')
            ->with('success', 'OTP sent successfully.');
    }

    public function resendOtp()
    {
        $countryCode = session('seller_register.country_code');
        $phone = session('seller_register.phone');

        if (! $countryCode || ! $phone) {
            return redirect()
                ->route('seller.register')
                ->with('error', 'Please start registration first.');
        }

        if (session('seller_register.otp_verified') === true) {
            return redirect()
                ->route('seller.register')
                ->with('error', 'OTP already verified.');
        }

        $otp = (string) random_int(100000, 999999);
        $expiresAt = now()->addMinutes(2);

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
            'seller_register.otp_expires_at' => $expiresAt->toIso8601String(),
        ]);

        return redirect()
            ->route('seller.register')
            ->with('success', 'OTP resent successfully.');
    }

    public function step2(Request $request)
    {
        $data = $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $countryCode = session('seller_register.country_code');
        $phone = session('seller_register.phone');

        if (! $countryCode || ! $phone) {
            throw ValidationException::withMessages([
                'otp' => 'Registration session expired. Please start again.',
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
            'seller_register.otp_verified' => true,
        ]);

        return redirect()
            ->route('seller.register')
            ->with('success', 'OTP verified successfully.');
    }

    public function step3(Request $request)
    {
        $data = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'referral_code' => ['nullable', 'string', 'max:100'],
        ]);

        if (session('seller_register.otp_verified') !== true) {
            return redirect()
                ->route('seller.register')
                ->with('error', 'Please verify OTP first.');
        }

        session([
            'seller_register.password' => Hash::make($data['password']),
            'seller_register.referral_code' => $data['referral_code'],
        ]);

        return redirect()
            ->route('seller.register')
            ->with('success', 'Password saved successfully.');
    }

    public function step4(Request $request)
    {
        $data = $request->validate([
            'shop_name' => ['required', 'string', 'max:255'],
            'owner_name' => ['required', 'string', 'max:255'],
            'owner_phone' => ['required', 'string', 'max:20'],
            'whatsapp_number' => ['nullable', 'string', 'max:20'],
        ]);

        if (session('seller_register.otp_verified') !== true) {
            return redirect()
                ->route('seller.register')
                ->with('error', 'Please verify OTP first.');
        }

        if (! session()->has('seller_register.password')) {
            return redirect()
                ->route('seller.register')
                ->with('error', 'Please set password first.');
        }

        $countryCode = session('seller_register.country_code');
        $phone = session('seller_register.phone');

        $exists = User::where('country_code', $countryCode)
            ->where('phone', $phone)
            ->exists();

        if ($exists) {
            return redirect()
                ->route('seller.register')
                ->with('error', 'Phone already registered.');
        }

        $user = DB::transaction(function () use ($data, $countryCode, $phone) {
            $user = User::create([
                'name' => $data['owner_name'],
                'full_name' => $data['owner_name'],
                'email' => null,
                'country_code' => $countryCode,
                'phone' => $phone,
                'phone_verified_at' => now(),
                'password' => session('seller_register.password'),

                'shop_name' => $data['shop_name'],
                'owner_name' => $data['owner_name'],
                'owner_phone' => preg_replace('/\D+/', '', $data['owner_phone']),
                'whatsapp_number' => $data['whatsapp_number']
                    ? preg_replace('/\D+/', '', $data['whatsapp_number'])
                    : null,
                'referral_code' => session('seller_register.referral_code') ?: null,
            ]);

            $user->assignRole('seller');

            return $user;
        });

        session()->forget('seller_register');

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect('/seller/dashboard')
            ->with('success', 'Seller account created successfully.');
    }
}
