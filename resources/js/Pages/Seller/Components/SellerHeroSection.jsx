export default function SellerHeroSection() {
  return (
    <section className="w-full bg-[#f8ddea]">
      <div className="mx-auto flex min-h-[282px] max-w-[1540px] items-center justify-between px-12 md:px-20 lg:px-[110px]">
        <div className="max-w-[620px]">
          <h1 className="max-w-[520px] text-[38px] font-extrabold leading-[1.12] tracking-[-0.02em] text-[#0c1320] md:text-[42px]">
            Grow Your Business with
            <br />
            Over 100+ Seller
          </h1>

          <p className="mt-3 text-[18px] leading-[1.4] text-[#f21875] md:text-[19px]">
            Create a seller account now and reach millions of customers!
          </p>
        </div>

        <div className="shrink-0">
          <button
            type="button"
            className="inline-flex h-[41px] min-w-[201px] items-center justify-center rounded-[4px] bg-[#f21875] px-6 text-[16px] font-semibold text-white shadow-none transition-opacity duration-200 hover:opacity-90"
          >
            Log In / Registration
          </button>
        </div>
      </div>
    </section>
  );
}