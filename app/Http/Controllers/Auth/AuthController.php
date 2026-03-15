<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PhoneOtp;
use App\Models\User;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function __construct(private SmsService $smsService)
    {
    }

    public function showLogin()
    {
        return Inertia::render('Auth/Login', [
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ],
        ]);
    }

    public function showRegister()
    {
        $step = 1;

        if (session('register.country_code') && session('register.phone')) {
            $step = 2;
        }

        if (session('register.otp_verified') === true) {
            $step = 3;
        }

        if (session('register.otp_verified') === true && session()->has('register.password')) {
            $step = 4;
        }

        return Inertia::render('Auth/Register', [
            'currentStep' => $step,
            'otpExpiresAt' => session('register.otp_expires_at'),
            'flash' => [
                'success' => session('success'),
                'error' => session('error'),
            ],
        ]);
    }

public function login(Request $request)
{
    $data = $request->validate([
        'country_code' => ['required', 'string'],
        'phone' => ['required', 'string'],
        'password' => ['required', 'string'],
    ]);

    $phone = preg_replace('/\D+/', '', $data['phone']);

    $user = User::where('country_code', $data['country_code'])
        ->where('phone', $phone)
        ->first();

    if (! $user || ! Hash::check($data['password'], $user->password)) {
        throw ValidationException::withMessages([
            'phone' => 'Invalid phone or password.',
        ]);
    }

    Auth::login($user, true);

    $request->session()->regenerate();

    return redirect('/dashboard');
}


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    

    public function registerStep1(Request $request)
    {
        $data = $request->validate([
            'country_code' => ['required', 'string'],
            'phone' => ['required', 'string'],
        ]);

        $phone = preg_replace('/\D+/', '', $data['phone']);

        $exists = User::where('country_code', $data['country_code'])
            ->where('phone', $phone)
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'phone' => 'Phone already registered.',
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

        $this->smsService->sendOtp($data['country_code'], $phone, $otp);

        session([
            'register.country_code' => $data['country_code'],
            'register.phone' => $phone,
            'register.otp_verified' => false,
            'register.otp_expires_at' => $expiresAt->toIso8601String(),
        ]);

        session()->forget('register.password');

        return redirect('/register')->with('success', 'OTP sent successfully.');
    }

    public function resendRegisterOtp()
    {
        $countryCode = session('register.country_code');
        $phone = session('register.phone');

        if (! $countryCode || ! $phone) {
            return redirect('/register')->with('error', 'Please start registration first.');
        }

        if (session('register.otp_verified') === true) {
            return redirect('/register')->with('error', 'OTP already verified.');
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

        $this->smsService->sendOtp($countryCode, $phone, $otp);

        session([
            'register.otp_expires_at' => $expiresAt->toIso8601String(),
        ]);

        return redirect('/register')->with('success', 'OTP resent successfully.');
    }

    public function registerStep2(Request $request)
    {
        $data = $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $countryCode = session('register.country_code');
        $phone = session('register.phone');

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
            'register.otp_verified' => true,
        ]);

        return redirect('/register')->with('success', 'OTP verified successfully.');
    }

    public function registerStep3(Request $request)
    {
        $data = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (session('register.otp_verified') !== true) {
            return redirect('/register')->with('error', 'Please verify OTP first.');
        }

        session([
            'register.password' => bcrypt($data['password']),
        ]);

        return redirect('/register')->with('success', 'Password saved successfully.');
    }

    public function registerStep4(Request $request)
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date'],
            'gender' => ['required', 'in:male,female,other'],
        ]);

        if (session('register.otp_verified') !== true) {
            return redirect('/register')->with('error', 'Please verify OTP first.');
        }

        if (! session()->has('register.password')) {
            return redirect('/register')->with('error', 'Please set password first.');
        }

        $user = User::create([
            'name' => $data['full_name'],
            'country_code' => session('register.country_code'),
            'phone' => session('register.phone'),
            'password' => session('register.password'),
            'full_name' => $data['full_name'],
            'date_of_birth' => $data['date_of_birth'],
            'gender' => $data['gender'],
            'phone_verified_at' => now(),
        ]);

        session()->forget('register');

        Auth::login($user);

        return redirect('/dashboard');
    }
}
