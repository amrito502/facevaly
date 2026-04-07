import { useEffect, useMemo, useRef, useState } from "react";
import { Link, router, useForm, usePage } from "@inertiajs/react";
import toast from "react-hot-toast";

export default function FacevalyHeroBanner() {
  const { currentStep, otpExpiresAt, sellerSession, flash } = usePage().props;

  const [timeLeft, setTimeLeft] = useState(0);
  const shownMessages = useRef({
    success: null,
    error: null,
  });

  useEffect(() => {
    if (flash?.success && shownMessages.current.success !== flash.success) {
      toast.success(flash.success);
      shownMessages.current.success = flash.success;
    }

    if (flash?.error && shownMessages.current.error !== flash.error) {
      toast.error(flash.error);
      shownMessages.current.error = flash.error;
    }
  }, [flash?.success, flash?.error]);

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
    <section className="relative min-h-screen w-full overflow-hidden bg-[#2b190f] text-white">
      <div className="absolute inset-0">
        <img
          src="/frontend/banner.png"
          alt="Facevaly seller hero"
          className="h-full w-full object-cover"
        />
        <div className="absolute inset-0 bg-black/20" />
      </div>

      <div className="relative z-10 mx-auto flex min-h-screen max-w-[1440px] items-center justify-between px-6 py-10 md:px-12 lg:px-20 xl:px-24">
        <div className="max-w-[520px] pt-4">
          <h1
            className="text-[58px] font-extrabold uppercase leading-[1.05] tracking-[0.01em] text-white md:text-[72px] lg:text-[82px] xl:text-[88px]"
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

          <div className="mt-14 flex items-start gap-8 text-white lg:gap-10">
            <StatBlock
              number="5"
              badge="M"
              label={"Monthly Active Users\non Facevaly"}
            />
            <StatBlock
              number="22"
              badge="M"
              label={"Live Products Across\n5 Countries"}
            />
            <StatBlock
              number="0"
              badge="%"
              label={"Platform commission\nfor 30 days"}
              noDivider
            />
          </div>
        </div>

        <div className="hidden w-full max-w-[540px] lg:block">
          <div className="rounded-[22px] bg-white px-11 py-9 text-[#2e3345] shadow-[0_10px_30px_rgba(0,0,0,0.15)]">
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
    </section>
  );
}

function StatBlock({ number, badge, label, noDivider = false }) {
  return (
    <div className="relative pr-8 last:pr-0 lg:pr-10">
      {!noDivider && (
        <div className="absolute right-0 top-5 h-[48px] w-px bg-white/80" />
      )}

      <div className="relative inline-flex items-end leading-none text-white">
        <span className="text-[86px] font-semibold tracking-[-0.04em]">
          {number}
        </span>
        <span className="absolute bottom-[10px] right-[-18px] inline-flex h-9 w-9 items-center justify-center rounded-full border-[3px] border-[#162b66] bg-white text-[22px] font-extrabold text-[#162b66]">
          {badge}
        </span>
      </div>

      <p className="mt-3 whitespace-pre-line text-center text-[15px] font-semibold leading-[1.25] text-white">
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
      <h2 className="text-[26px] font-bold leading-tight text-[#2f3446]">
        Sign up as a Facevaly Seller
      </h2>

      <p className="mt-2 text-[14px] text-[#8b93a7]">
        Already have an account?{" "}
        <Link href="/login" className="font-semibold text-[#ff6a00]">
          Log in
        </Link>
      </p>

      <div className="mt-7 rounded-[14px] border border-[#ff6f61] bg-white px-4 py-3">
        <div className="flex items-center justify-between gap-3">
          <input
            type="text"
            value={form.data.phone}
            onChange={(e) => form.setData("phone", e.target.value)}
            placeholder="9xxxx"
            className="w-full border-0 bg-transparent text-[18px] font-semibold text-[#7f8697] outline-none placeholder:text-[#9ea4b0]"
          />

          <div className="flex items-center gap-3 pl-3">
            <span className="inline-flex h-6 w-6 items-center justify-center rounded-full bg-[#0f8b4c] text-[11px] font-bold text-white">
              ●
            </span>
            <input
              type="text"
              value={form.data.country_code}
              onChange={(e) => form.setData("country_code", e.target.value)}
              className="w-[60px] border-0 bg-transparent text-[16px] font-semibold text-[#9a9a9a] outline-none"
            />
          </div>
        </div>
      </div>

      {form.errors.phone && (
        <p className="mt-2 text-[14px] font-medium text-[#ff6f61]">
          {form.errors.phone}
        </p>
      )}

      <button
        type="submit"
        disabled={form.processing}
        className="mt-8 inline-flex h-[52px] w-full items-center justify-center rounded-[10px] bg-[#ff5a00] text-[17px] font-bold text-white transition-opacity duration-200 hover:opacity-95 disabled:opacity-60"
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
      <h2 className="text-[26px] font-bold leading-tight text-[#2f3446]">
        Verify your phone
      </h2>

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
          className="h-[52px] w-full rounded-[10px] border border-[#cfd6e4] px-4 text-[18px] text-[#4c556a] outline-none"
        />

        {form.errors.otp && (
          <p className="mt-2 text-[14px] font-medium text-[#ff6f61]">
            {form.errors.otp}
          </p>
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
          className="font-semibold text-[#ff6a00] disabled:cursor-not-allowed disabled:opacity-50"
        >
          Resend OTP
        </button>
      </div>

      <button
        type="submit"
        disabled={form.processing}
        className="mt-8 inline-flex h-[52px] w-full items-center justify-center rounded-[10px] bg-[#ff5a00] text-[17px] font-bold text-white transition-opacity duration-200 hover:opacity-95 disabled:opacity-60"
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
      <h2 className="text-[26px] font-bold leading-tight text-[#2f3446]">
        Sign up as a Seller
      </h2>

      <p className="mt-2 text-[14px] text-[#8b93a7]">
        Create a Password to continue
      </p>

      <div className="mt-8 space-y-5">
        <div>
          <input
            type="password"
            value={form.data.password}
            onChange={(e) => form.setData("password", e.target.value)}
            placeholder="Create Password *"
            className="h-[52px] w-full rounded-[10px] border border-[#cfd6e4] px-4 text-[18px] text-[#4c556a] outline-none"
          />
          {form.errors.password && (
            <p className="mt-2 text-[14px] font-medium text-[#ff6f61]">
              {form.errors.password}
            </p>
          )}
        </div>

        <div>
          <input
            type="password"
            value={form.data.password_confirmation}
            onChange={(e) =>
              form.setData("password_confirmation", e.target.value)
            }
            placeholder="Confirm Password *"
            className="h-[52px] w-full rounded-[10px] border border-[#cfd6e4] px-4 text-[18px] text-[#4c556a] outline-none"
          />
        </div>
      </div>

      <button
        type="submit"
        disabled={form.processing}
        className="mt-8 inline-flex h-[52px] w-full items-center justify-center rounded-[10px] bg-[#ff5a00] text-[17px] font-bold text-white transition-opacity duration-200 hover:opacity-95 disabled:opacity-60"
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
      <h2 className="text-[26px] font-bold leading-tight text-[#2f3446]">
        Tell us about you
      </h2>

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
            className="h-[52px] w-full rounded-[10px] border border-[#cfd6e4] px-4 text-[18px] text-[#4c556a] outline-none"
          />
          {form.errors.full_name && (
            <p className="mt-2 text-[14px] font-medium text-[#ff6f61]">
              {form.errors.full_name}
            </p>
          )}
        </div>

        <div>
          <input
            type="email"
            value={form.data.email}
            onChange={(e) => form.setData("email", e.target.value)}
            placeholder="Email address *"
            className="h-[52px] w-full rounded-[10px] border border-[#cfd6e4] px-4 text-[18px] text-[#4c556a] outline-none"
          />
          {form.errors.email && (
            <p className="mt-2 text-[14px] font-medium text-[#ff6f61]">
              {form.errors.email}
            </p>
          )}
        </div>
      </div>

      <button
        type="submit"
        disabled={form.processing}
        className="mt-8 inline-flex h-[52px] w-full items-center justify-center rounded-[10px] bg-[#ff5a00] text-[17px] font-bold text-white transition-opacity duration-200 hover:opacity-95 disabled:opacity-60"
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
      <h2 className="text-[26px] font-bold leading-tight text-[#2f3446]">
        Tell us about your business
      </h2>

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
            className="h-[52px] w-full rounded-[10px] border border-[#cfd6e4] px-4 text-[18px] text-[#4c556a] outline-none"
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
            className="h-[52px] w-full rounded-[10px] border border-[#cfd6e4] px-4 text-[18px] text-[#4c556a] outline-none"
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
            className="w-full rounded-[10px] border border-[#cfd6e4] px-4 py-3 text-[18px] text-[#4c556a] outline-none"
          />
          {form.errors.address && (
            <p className="mt-2 text-[14px] font-medium text-[#ff6f61]">
              {form.errors.address}
            </p>
          )}
        </div>
      </div>

      <button
        type="submit"
        disabled={form.processing}
        className="mt-8 inline-flex h-[52px] w-full items-center justify-center rounded-[10px] bg-[#ff5a00] text-[17px] font-bold text-white transition-opacity duration-200 hover:opacity-95 disabled:opacity-60"
      >
        {form.processing ? "Submitting..." : "Submit"}
      </button>
    </form>
  );
}