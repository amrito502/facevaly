 import { Head, Link, router, useForm, usePage } from '@inertiajs/react';

export default function SellerRegister(props) {
    const {
        currentStep = 1,
        otpExpiresAt = null,
        sellerSession: rawSellerSession = {},
        flash = {},
    } = props;

    const sellerSession = {
        country_code: '+880',
        phone: '',
        otp_verified: false,
        password_saved: false,
        referral_code: '',
        shop_name: '',
        owner_name: '',
        owner_phone: '',
        whatsapp_number: '',
        ...rawSellerSession,
    };

    const page = usePage();
    const errors = page.props.errors || {};

    const step1Form = useForm({
        country_code: sellerSession.country_code || '+880',
        phone: sellerSession.phone || '',
    });

    const step2Form = useForm({
        otp: '',
    });

    const step3Form = useForm({
        password: '',
        password_confirmation: '',
        referral_code: sellerSession.referral_code || '',
    });

    const step4Form = useForm({
        shop_name: sellerSession.shop_name || '',
        owner_name: sellerSession.owner_name || '',
        owner_phone: sellerSession.owner_phone || sellerSession.phone || '',
        whatsapp_number: sellerSession.whatsapp_number || '',
    });

    const submitStep1 = (e) => {
        e.preventDefault();
        step1Form.post('/become-seller/step-1');
    };

    const submitStep2 = (e) => {
        e.preventDefault();
        step2Form.post('/become-seller/step-2');
    };

    const submitStep3 = (e) => {
        e.preventDefault();
        step3Form.post('/become-seller/step-3');
    };

    const submitStep4 = (e) => {
        e.preventDefault();
        step4Form.post('/become-seller/step-4');
    };

    const resendOtp = () => {
        router.post('/become-seller/resend-otp');
    };

    return (
        <>
            <Head title="Become a Seller" />

            <div className="min-h-screen bg-gray-100 py-10">
                <div className="mx-auto max-w-xl rounded-2xl bg-white p-6 shadow">
                    <h1 className="mb-2 text-2xl font-bold">Become a Seller</h1>
                    <p className="mb-6 text-sm text-gray-500">
                        Complete the registration steps to become a seller
                    </p>

                    {flash?.success && (
                        <div className="mb-4 rounded-lg bg-green-100 px-4 py-3 text-sm text-green-700">
                            {flash.success}
                        </div>
                    )}

                    {flash?.error && (
                        <div className="mb-4 rounded-lg bg-red-100 px-4 py-3 text-sm text-red-700">
                            {flash.error}
                        </div>
                    )}

                    <div className="mb-6 grid grid-cols-4 gap-2 text-sm">
                        <div className={`rounded-lg px-3 py-2 text-center ${currentStep >= 1 ? 'bg-black text-white' : 'bg-gray-200 text-gray-600'}`}>
                            Phone
                        </div>
                        <div className={`rounded-lg px-3 py-2 text-center ${currentStep >= 2 ? 'bg-black text-white' : 'bg-gray-200 text-gray-600'}`}>
                            OTP
                        </div>
                        <div className={`rounded-lg px-3 py-2 text-center ${currentStep >= 3 ? 'bg-black text-white' : 'bg-gray-200 text-gray-600'}`}>
                            Password
                        </div>
                        <div className={`rounded-lg px-3 py-2 text-center ${currentStep >= 4 ? 'bg-black text-white' : 'bg-gray-200 text-gray-600'}`}>
                            Details
                        </div>
                    </div>

                    {currentStep === 1 && (
                        <form onSubmit={submitStep1} className="space-y-4">
                            <div>
                                <label className="mb-1 block text-sm font-medium">Country Code</label>
                                <select
                                    value={step1Form.data.country_code}
                                    onChange={(e) => step1Form.setData('country_code', e.target.value)}
                                    className="w-full rounded-lg border border-gray-300 px-3 py-2"
                                >
                                    <option value="+880">Bangladesh (+880)</option>
                                    <option value="+91">India (+91)</option>
                                    <option value="+1">USA (+1)</option>
                                    <option value="+44">UK (+44)</option>
                                </select>
                                {errors.country_code && (
                                    <div className="mt-1 text-sm text-red-600">{errors.country_code}</div>
                                )}
                            </div>

                            <div>
                                <label className="mb-1 block text-sm font-medium">Phone Number</label>
                                <input
                                    type="text"
                                    value={step1Form.data.phone}
                                    onChange={(e) => step1Form.setData('phone', e.target.value)}
                                    className="w-full rounded-lg border border-gray-300 px-3 py-2"
                                    placeholder="Enter phone number"
                                />
                                {errors.phone && (
                                    <div className="mt-1 text-sm text-red-600">{errors.phone}</div>
                                )}
                            </div>

                            <button
                                type="submit"
                                disabled={step1Form.processing}
                                className="w-full rounded-lg bg-black px-4 py-2 text-white"
                            >
                                {step1Form.processing ? 'Sending OTP...' : 'Continue'}
                            </button>
                        </form>
                    )}

                    {currentStep === 2 && (
                        <form onSubmit={submitStep2} className="space-y-4">
                            <div className="rounded-lg bg-gray-100 px-4 py-3 text-sm">
                                <p><strong>Phone:</strong> {sellerSession.country_code} {sellerSession.phone}</p>
                            </div>

                            <div>
                                <label className="mb-1 block text-sm font-medium">OTP</label>
                                <input
                                    type="text"
                                    maxLength={6}
                                    value={step2Form.data.otp}
                                    onChange={(e) => step2Form.setData('otp', e.target.value)}
                                    className="w-full rounded-lg border border-gray-300 px-3 py-2"
                                    placeholder="Enter 6 digit OTP"
                                />
                                {errors.otp && (
                                    <div className="mt-1 text-sm text-red-600">{errors.otp}</div>
                                )}
                            </div>

                            {otpExpiresAt && (
                                <p className="text-xs text-gray-500">
                                    OTP expires at: {otpExpiresAt}
                                </p>
                            )}

                            <div className="flex gap-3">
                                <button
                                    type="submit"
                                    disabled={step2Form.processing}
                                    className="flex-1 rounded-lg bg-black px-4 py-2 text-white"
                                >
                                    {step2Form.processing ? 'Verifying...' : 'Verify OTP'}
                                </button>

                                <button
                                    type="button"
                                    onClick={resendOtp}
                                    className="rounded-lg bg-gray-200 px-4 py-2 text-gray-800"
                                >
                                    Resend OTP
                                </button>
                            </div>
                        </form>
                    )}

                    {currentStep === 3 && (
                        <form onSubmit={submitStep3} className="space-y-4">
                            <div className="rounded-lg bg-gray-100 px-4 py-3 text-sm">
                                <p><strong>Verified Phone:</strong> {sellerSession.country_code} {sellerSession.phone}</p>
                            </div>

                            <div>
                                <label className="mb-1 block text-sm font-medium">Password</label>
                                <input
                                    type="password"
                                    value={step3Form.data.password}
                                    onChange={(e) => step3Form.setData('password', e.target.value)}
                                    className="w-full rounded-lg border border-gray-300 px-3 py-2"
                                    placeholder="Enter password"
                                />
                                {errors.password && (
                                    <div className="mt-1 text-sm text-red-600">{errors.password}</div>
                                )}
                            </div>

                            <div>
                                <label className="mb-1 block text-sm font-medium">Confirm Password</label>
                                <input
                                    type="password"
                                    value={step3Form.data.password_confirmation}
                                    onChange={(e) => step3Form.setData('password_confirmation', e.target.value)}
                                    className="w-full rounded-lg border border-gray-300 px-3 py-2"
                                    placeholder="Confirm password"
                                />
                            </div>

                            <div>
                                <label className="mb-1 block text-sm font-medium">Referral Code</label>
                                <input
                                    type="text"
                                    value={step3Form.data.referral_code}
                                    onChange={(e) => step3Form.setData('referral_code', e.target.value)}
                                    className="w-full rounded-lg border border-gray-300 px-3 py-2"
                                    placeholder="Optional referral code"
                                />
                                {errors.referral_code && (
                                    <div className="mt-1 text-sm text-red-600">{errors.referral_code}</div>
                                )}
                            </div>

                            <button
                                type="submit"
                                disabled={step3Form.processing}
                                className="w-full rounded-lg bg-black px-4 py-2 text-white"
                            >
                                {step3Form.processing ? 'Saving...' : 'Continue'}
                            </button>
                        </form>
                    )}

                    {currentStep === 4 && (
                        <form onSubmit={submitStep4} className="space-y-4">
                            <div>
                                <label className="mb-1 block text-sm font-medium">Shop Name</label>
                                <input
                                    type="text"
                                    value={step4Form.data.shop_name}
                                    onChange={(e) => step4Form.setData('shop_name', e.target.value)}
                                    className="w-full rounded-lg border border-gray-300 px-3 py-2"
                                    placeholder="Enter shop name"
                                />
                                {errors.shop_name && (
                                    <div className="mt-1 text-sm text-red-600">{errors.shop_name}</div>
                                )}
                            </div>

                            <div>
                                <label className="mb-1 block text-sm font-medium">Owner Name</label>
                                <input
                                    type="text"
                                    value={step4Form.data.owner_name}
                                    onChange={(e) => step4Form.setData('owner_name', e.target.value)}
                                    className="w-full rounded-lg border border-gray-300 px-3 py-2"
                                    placeholder="Enter owner name"
                                />
                                {errors.owner_name && (
                                    <div className="mt-1 text-sm text-red-600">{errors.owner_name}</div>
                                )}
                            </div>

                            <div>
                                <label className="mb-1 block text-sm font-medium">Owner Phone</label>
                                <input
                                    type="text"
                                    value={step4Form.data.owner_phone}
                                    onChange={(e) => step4Form.setData('owner_phone', e.target.value)}
                                    className="w-full rounded-lg border border-gray-300 px-3 py-2"
                                    placeholder="Enter owner phone"
                                />
                                {errors.owner_phone && (
                                    <div className="mt-1 text-sm text-red-600">{errors.owner_phone}</div>
                                )}
                            </div>

                            <div>
                                <label className="mb-1 block text-sm font-medium">WhatsApp Number</label>
                                <input
                                    type="text"
                                    value={step4Form.data.whatsapp_number}
                                    onChange={(e) => step4Form.setData('whatsapp_number', e.target.value)}
                                    className="w-full rounded-lg border border-gray-300 px-3 py-2"
                                    placeholder="Enter WhatsApp number"
                                />
                                {errors.whatsapp_number && (
                                    <div className="mt-1 text-sm text-red-600">{errors.whatsapp_number}</div>
                                )}
                            </div>

                            <button
                                type="submit"
                                disabled={step4Form.processing}
                                className="w-full rounded-lg bg-black px-4 py-2 text-white"
                            >
                                {step4Form.processing ? 'Creating Account...' : 'Create Seller Account'}
                            </button>
                        </form>
                    )}

                    <div className="mt-6 text-center text-sm text-gray-600">
                        Already have an account?{' '}
                        <Link href="/login" className="font-medium text-black underline">
                            Login
                        </Link>
                    </div>
                </div>
            </div>
        </>
    );
}
