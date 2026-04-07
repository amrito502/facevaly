<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PhoneOtp;
use App\Models\Shop;
use App\Models\User;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
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

        if (
            session('seller_register.otp_verified') === true &&
            session()->has('seller_register.password') &&
            session()->has('seller_register.full_name') &&
            session()->has('seller_register.email')
        ) {
            $step = 5;
        }

        return Inertia::render('Seller/Home/Index', [
            'currentStep' => $step,
            'otpExpiresAt' => session('seller_register.otp_expires_at'),
            'sellerSession' => [
                'country_code' => session('seller_register.country_code', '+880'),
                'phone' => session('seller_register.phone', ''),
                'otp_verified' => session('seller_register.otp_verified', false),

                'full_name' => session('seller_register.full_name', ''),
                'email' => session('seller_register.email', ''),

                'business_name' => session('seller_register.business_name', ''),
                'website_url' => session('seller_register.website_url', ''),
                'address' => session('seller_register.address', ''),
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
            'seller_register.full_name',
            'seller_register.email',
            'seller_register.business_name',
            'seller_register.website_url',
            'seller_register.address',
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
        ]);

        if (session('seller_register.otp_verified') !== true) {
            return redirect()
                ->route('seller.register')
                ->with('error', 'Please verify OTP first.');
        }

        session([
            'seller_register.password' => Hash::make($data['password']),
        ]);

        return redirect()
            ->route('seller.register')
            ->with('success', 'Password saved successfully.');
    }

    public function step4(Request $request)
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
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

        session([
            'seller_register.full_name' => $data['full_name'],
            'seller_register.email' => $data['email'],
        ]);

        return redirect()
            ->route('seller.register')
            ->with('success', 'Personal information saved successfully.');
    }

    public function step5(Request $request)
    {
        $data = $request->validate([
            'business_name' => ['required', 'string', 'max:255'],
            'website_url' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:1000'],
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

        if (! session()->has('seller_register.full_name') || ! session()->has('seller_register.email')) {
            return redirect()
                ->route('seller.register')
                ->with('error', 'Please complete personal information first.');
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
                'name' => session('seller_register.full_name'),
                'full_name' => session('seller_register.full_name'),
                'email' => session('seller_register.email'),
                'country_code' => $countryCode,
                'phone' => $phone,
                'phone_verified_at' => now(),
                'password' => session('seller_register.password'),
            ]);

            if (method_exists($user, 'assignRole')) {
                $user->assignRole('seller');
            }

            Shop::create([
                'seller_id' => $user->id,
                'shop_name' => $data['business_name'],
                'slug' => $this->generateUniqueSlug($data['business_name']),
                'website_url' => $data['website_url'],
                'address' => $data['address'],
                'shop_email' => session('seller_register.email'),
                'shop_phone' => $countryCode . $phone,
                'status' => 'draft',
                'verification_status' => 'pending',
            ]);

            return $user;
        });

        session()->forget('seller_register');

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect('/seller/dashboard')
            ->with('success', 'Seller account created successfully.');
    }

    private function generateUniqueSlug(string $name): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug ?: 'shop';
        $counter = 1;

        while (Shop::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}