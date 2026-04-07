import { useEffect, useMemo, useState } from "react";
import { Head, Link, router, useForm, usePage } from "@inertiajs/react";

export default function SellerRegister() {
  const { currentStep, otpExpiresAt, sellerSession, flash } = usePage().props;

  const [timeLeft, setTimeLeft] = useState(0);

  useEffect(() => {
    if (!otpExpiresAt) return;

    const tick = () => {
      const end = new Date(otpExpiresAt).getTime();
      const now = Date.now();
      const diff = Math.max(0, Math.floor((end - now) / 1000));
      setTimeLeft(diff);
    };

    tick();
    const interval = setInterval(tick, 1000);

    return () => clearInterval(interval);
  }, [otpExpiresAt]);

  const formattedTime = useMemo(() => {
    const minutes = String(Math.floor(timeLeft / 60)).padStart(2, "0");
    const seconds = String(timeLeft % 60).padStart(2, "0");
    return `${minutes}:${seconds}`;
  }, [timeLeft]);

  return (
    <>
      <Head title="Seller Register" />

      <div className="min-h-screen bg-[#005c4d] px-4 py-10">
        <div className="mx-auto max-w-[980px]">
          <div className="grid items-center gap-10 lg:grid-cols-2">
            <HeroSection />

            <div className="rounded-[6px] bg-white p-6 md:p-8">
              {flash?.success && (
                <div className="mb-4 rounded-md bg-green-50 px-4 py-3 text-sm text-green-700">
                  {flash.success}
                </div>
              )}

              {flash?.error && (
                <div className="mb-4 rounded-md bg-red-50 px-4 py-3 text-sm text-red-700">
                  {flash.error}
                </div>
              )}

              {currentStep === 1 && <StepPhone sellerSession={sellerSession} />}
              {currentStep === 2 && (
                <StepOtp
                  sellerSession={sellerSession}
                  timeLeft={timeLeft}
                  formattedTime={formattedTime}
                />
              )}
              {currentStep === 3 && <StepPassword />}
              {currentStep === 4 && <StepProfile sellerSession={sellerSession} />}
              {currentStep === 5 && <StepBusiness sellerSession={sellerSession} />}
            </div>
          </div>
        </div>
      </div>
    </>
  );
}

function HeroSection() {
  return (
    <section className="relative hidden min-h-[650px] overflow-hidden rounded-[8px] lg:block">
      <img
        src="/frontend/banner.png"
        alt="Facevaly seller hero"
        className="absolute inset-0 h-full w-full object-cover"
      />
      <div className="absolute inset-0 bg-black/20" />

      <div className="relative z-10 flex h-full flex-col justify-center px-10 py-12 text-white">
        <h1
          className="text-[54px] font-extrabold uppercase leading-[1.05] xl:text-[72px]"
          style={{
            textShadow:
              "0 0 6px rgba(255,124,59,0.95), 0 0 14px rgba(255,124,59,0.55)",
          }}
        >
          Grow Your
          <br />
          Business
          <br />
          With Us!
        </h1>

        <div className="mt-12 flex gap-8">
          <StatBlock number="5" badge="M" label={"Monthly Active Users\non Facevaly"} />
          <StatBlock number="22" badge="M" label={"Live Products Across\n5 Countries"} />
          <StatBlock number="0" badge="%" label={"Platform commission\nfor 30 days"} noDivider />
        </div>
      </div>
    </section>
  );
}

function StatBlock({ number, badge, label, noDivider = false }) {
  return (
    <div className="relative pr-8">
      {!noDivider && (
        <div className="absolute right-0 top-5 h-[48px] w-px bg-white/80" />
      )}

      <div className="relative inline-flex items-end leading-none text-white">
        <span className="text-[70px] font-semibold tracking-[-0.04em]">{number}</span>
        <span className="absolute bottom-[8px] right-[-18px] inline-flex h-9 w-9 items-center justify-center rounded-full border-[3px] border-[#162b66] bg-white text-[20px] font-extrabold text-[#162b66]">
          {badge}
        </span>
      </div>

      <p className="mt-3 whitespace-pre-line text-center text-[14px] font-semibold leading-[1.25] text-white">
        {label}
      </p>
    </div>
  );
}

function StepPhone({ sellerSession }) {
  const form = useForm({
    country_code: sellerSession?.country_code || "+880",
    phone: sellerSession?.phone || "",
  });

  const submit = (e) => {
    e.preventDefault();
    form.post("/become-seller/step-1");
  };

  return (
    <form onSubmit={submit}>
      <h2 className="text-[26px] font-bold text-[#1f2940]">Sign up as a Facevaly Seller</h2>
      <p className="mt-2 text-[14px] text-[#8b93a7]">
        Already have an account?{" "}
        <Link href="/login" className="font-semibold text-[#ff6a00]">
          Log in
        </Link>
      </p>

      <div className="mt-7 rounded-[12px] border border-[#ff6f61] px-4 py-3">
        <div className="flex items-center gap-3">
          <input
            type="text"
            value={form.data.phone}
            onChange={(e) => form.setData("phone", e.target.value)}
            placeholder="9xxxx"
            className="w-full border-0 bg-transparent text-[18px] font-semibold text-[#7f8697] outline-none placeholder:text-[#9ea4b0]"
          />
          <input
            type="text"
            value={form.data.country_code}
            onChange={(e) => form.setData("country_code", e.target.value)}
            className="w-[80px] border-0 bg-transparent text-right text-[16px] font-semibold text-[#9a9a9a] outline-none"
          />
        </div>
      </div>

      {form.errors.phone && (
        <p className="mt-2 text-[14px] font-medium text-[#ff6f61]">{form.errors.phone}</p>
      )}

      <button
        type="submit"
        disabled={form.processing}
        className="mt-8 inline-flex h-[52px] w-full items-center justify-center rounded-[10px] bg-[#ff5a00] text-[17px] font-bold text-white disabled:opacity-60"
      >
        {form.processing ? "Sending..." : "Verify with SMS"}
      </button>

      <p className="mt-5 text-[13px] leading-7 text-[#8e95a3]">
        By clicking Login, you agree to these{" "}
        <a href="#" className="font-medium text-[#ff6a00]">
          Terms &amp; Conditions
        </a>{" "}
        and{" "}
        <a href="#" className="font-medium text-[#ff6a00]">
          Privacy Policy
        </a>
      </p>
    </form>
  );
}

function StepOtp({ sellerSession, timeLeft, formattedTime }) {
  const form = useForm({
    otp: "",
  });

  const submit = (e) => {
    e.preventDefault();
    form.post("/become-seller/step-2");
  };

  const resendOtp = () => {
    router.post("/become-seller/resend-otp");
  };

  return (
    <form onSubmit={submit}>
      <h2 className="text-[26px] font-bold text-[#1f2940]">Verify your phone</h2>
      <p className="mt-2 text-[14px] text-[#8b93a7]">
        Enter the OTP sent to {sellerSession?.country_code} {sellerSession?.phone}
      </p>

      <div className="mt-7">
        <input
          type="text"
          maxLength={6}
          value={form.data.otp}
          onChange={(e) => form.setData("otp", e.target.value)}
          placeholder="Enter 6 digit OTP"
          className="h-[52px] w-full rounded-[8px] border border-[#cfd6e4] px-4 text-[18px] text-[#4c556a] outline-none"
        />
        {form.errors.otp && (
          <p className="mt-2 text-[14px] font-medium text-[#ff6f61]">{form.errors.otp}</p>
        )}
      </div>

      <div className="mt-4 flex items-center justify-between text-sm">
        <span className="text-[#6f7890]">
          {timeLeft > 0 ? `Resend in ${formattedTime}` : "Didn't receive OTP?"}
        </span>

        <button
          type="button"
          onClick={resendOtp}
          disabled={timeLeft > 0}
          className="font-semibold text-[#14b8a6] disabled:cursor-not-allowed disabled:opacity-50"
        >
          Resend OTP
        </button>
      </div>

      <button
        type="submit"
        disabled={form.processing}
        className="mt-8 inline-flex h-[52px] w-full items-center justify-center rounded-[6px] bg-[#14b8a6] text-[17px] font-bold text-white disabled:opacity-60"
      >
        {form.processing ? "Verifying..." : "Submit"}
      </button>
    </form>
  );
}

function StepPassword() {
  const form = useForm({
    password: "",
    password_confirmation: "",
  });

  const submit = (e) => {
    e.preventDefault();
    form.post("/become-seller/step-3");
  };

  return (
    <form onSubmit={submit}>
      <h2 className="text-[26px] font-bold text-[#1f2940]">Sign up as a Seller</h2>
      <p className="mt-2 text-[14px] text-[#8b93a7]">Create a Password to continue</p>

      <div className="mt-8 space-y-5">
        <div>
          <input
            type="password"
            value={form.data.password}
            onChange={(e) => form.setData("password", e.target.value)}
            placeholder="Create Password *"
            className="h-[52px] w-full rounded-[6px] border border-[#cfd6e4] px-4 text-[18px] text-[#4c556a] outline-none"
          />
          {form.errors.password && (
            <p className="mt-2 text-[14px] font-medium text-[#ff6f61]">{form.errors.password}</p>
          )}
        </div>

        <div>
          <input
            type="password"
            value={form.data.password_confirmation}
            onChange={(e) => form.setData("password_confirmation", e.target.value)}
            placeholder="Confirm Password *"
            className="h-[52px] w-full rounded-[6px] border border-[#cfd6e4] px-4 text-[18px] text-[#4c556a] outline-none"
          />
        </div>
      </div>

      <button
        type="submit"
        disabled={form.processing}
        className="mt-8 inline-flex h-[52px] w-full items-center justify-center rounded-[6px] bg-[#14b8a6] text-[17px] font-bold text-white disabled:opacity-60"
      >
        {form.processing ? "Saving..." : "Submit"}
      </button>
    </form>
  );
}

function StepProfile({ sellerSession }) {
  const form = useForm({
    full_name: sellerSession?.full_name || "",
    email: sellerSession?.email || "",
  });

  const submit = (e) => {
    e.preventDefault();
    form.post("/become-seller/step-4");
  };

  return (
    <form onSubmit={submit}>
      <h2 className="text-[26px] font-bold text-[#1f2940]">Tell us about you</h2>
      <p className="mt-2 text-[14px] text-[#8b93a7]">
        Information required to start selling with us
      </p>

      <div className="mt-8 space-y-5">
        <div>
          <input
            type="text"
            value={form.data.full_name}
            onChange={(e) => form.setData("full_name", e.target.value)}
            placeholder="Full Name *"
            className="h-[52px] w-full rounded-[6px] border border-[#cfd6e4] px-4 text-[18px] text-[#4c556a] outline-none"
          />
          {form.errors.full_name && (
            <p className="mt-2 text-[14px] font-medium text-[#ff6f61]">{form.errors.full_name}</p>
          )}
        </div>

        <div>
          <input
            type="email"
            value={form.data.email}
            onChange={(e) => form.setData("email", e.target.value)}
            placeholder="Email address *"
            className="h-[52px] w-full rounded-[6px] border border-[#cfd6e4] px-4 text-[18px] text-[#4c556a] outline-none"
          />
          {form.errors.email && (
            <p className="mt-2 text-[14px] font-medium text-[#ff6f61]">{form.errors.email}</p>
          )}
        </div>
      </div>

      <button
        type="submit"
        disabled={form.processing}
        className="mt-8 inline-flex h-[52px] w-full items-center justify-center rounded-[6px] bg-[#14b8a6] text-[17px] font-bold text-white disabled:opacity-60"
      >
        {form.processing ? "Saving..." : "Next"}
      </button>
    </form>
  );
}

function StepBusiness({ sellerSession }) {
  const form = useForm({
    business_name: sellerSession?.business_name || "",
    website_url: sellerSession?.website_url || "",
    address: sellerSession?.address || "",
  });

  const submit = (e) => {
    e.preventDefault();
    form.post("/become-seller/step-5");
  };

  return (
    <form onSubmit={submit}>
      <h2 className="text-[26px] font-bold text-[#1f2940]">Tell us about your business</h2>
      <p className="mt-2 text-[14px] text-[#8b93a7]">
        Information required to start selling with us
      </p>

      <div className="mt-8 space-y-5">
        <div>
          <input
            type="text"
            value={form.data.business_name}
            onChange={(e) => form.setData("business_name", e.target.value)}
            placeholder="Business or Shop name *"
            className="h-[52px] w-full rounded-[6px] border border-[#cfd6e4] px-4 text-[18px] text-[#4c556a] outline-none"
          />
          {form.errors.business_name && (
            <p className="mt-2 text-[14px] font-medium text-[#ff6f61]">
              {form.errors.business_name}
            </p>
          )}
        </div>

        <div>
          <input
            type="text"
            value={form.data.website_url}
            onChange={(e) => form.setData("website_url", e.target.value)}
            placeholder="Website or Facebook page link *"
            className="h-[52px] w-full rounded-[6px] border border-[#cfd6e4] px-4 text-[18px] text-[#4c556a] outline-none"
          />
          {form.errors.website_url && (
            <p className="mt-2 text-[14px] font-medium text-[#ff6f61]">
              {form.errors.website_url}
            </p>
          )}
        </div>

        <div>
          <textarea
            value={form.data.address}
            onChange={(e) => form.setData("address", e.target.value)}
            placeholder="Address *"
            rows={4}
            className="w-full rounded-[6px] border border-[#cfd6e4] px-4 py-3 text-[18px] text-[#4c556a] outline-none"
          />
          {form.errors.address && (
            <p className="mt-2 text-[14px] font-medium text-[#ff6f61]">{form.errors.address}</p>
          )}
        </div>
      </div>

      <button
        type="submit"
        disabled={form.processing}
        className="mt-8 inline-flex h-[52px] w-full items-center justify-center rounded-[6px] bg-[#14b8a6] text-[17px] font-bold text-white disabled:opacity-60"
      >
        {form.processing ? "Submitting..." : "Submit"}
      </button>
    </form>
  );
}