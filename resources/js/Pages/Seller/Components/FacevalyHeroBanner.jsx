export default function FacevalyHeroBanner() {
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
            <StatBlock number="5" badge="M" label="Monthly Active Users\non Facevaly" />
            <StatBlock number="22" badge="M" label="Live Products Across\n5 Countries" />
            <StatBlock number="0" badge="%" label="Platform commission\nfor 30 days" noDivider />
          </div>
        </div>

        <div className="hidden w-full max-w-[540px] lg:block">
          <div className="rounded-[22px] bg-white px-11 py-9 text-[#2e3345] shadow-[0_10px_30px_rgba(0,0,0,0.15)]">
            <h2 className="text-[26px] font-bold leading-tight text-[#2f3446]">
              Sign up as a Facevaly Seller
            </h2>

            <p className="mt-2 text-[14px] text-[#8b93a7]">
              Already have an account? Click to {" "}
              <a href="#" className="font-semibold text-[#ff6a00]">
                Log in
              </a>
            </p>

            <div className="mt-7 rounded-[14px] border border-[#ff6f61] bg-white px-4 py-3">
              <div className="flex items-center justify-between gap-3">
                <input
                  type="text"
                  placeholder="9xxxx"
                  className="w-full border-0 bg-transparent text-[18px] font-semibold text-[#7f8697] outline-none placeholder:text-[#9ea4b0]"
                />

                <div className="flex items-center gap-3 pl-3">
                  <span className="inline-flex h-6 w-6 items-center justify-center rounded-full bg-[#0f8b4c] text-[11px] font-bold text-white">
                    ●
                  </span>
                  <span className="text-[16px] font-semibold text-[#9a9a9a]">+880</span>
                </div>
              </div>
            </div>

            <p className="mt-2 text-[14px] font-medium text-[#ff6f61]">
              Phone is Required
            </p>

            <button
              type="button"
              className="mt-8 inline-flex h-[52px] w-full items-center justify-center rounded-[10px] bg-[#ff5a00] text-[17px] font-bold text-white transition-opacity duration-200 hover:opacity-95"
            >
              Verify with SMS
            </button>

            <p className="mt-5 text-[13px] leading-7 text-[#8e95a3]">
              By clicking Login, you agree to these {" "}
              <a href="#" className="font-medium text-[#ff6a00]">
                Terms &amp; Conditions
              </a>{" "}
              and {" "}
              <a href="#" className="font-medium text-[#ff6a00]">
                Privacy Policy
              </a>
            </p>
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
        <span className="text-[86px] font-semibold tracking-[-0.04em]">{number}</span>
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
