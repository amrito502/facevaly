export default function FacevalyCtaBanner() {
  return (
    <section className="w-full bg-[#ff5a14] py-4">
      <div className="mx-auto flex min-h-[133px] max-w-[1920px] items-center justify-between px-6 md:px-12 lg:px-20 xl:px-28 2xl:px-40">
        <div className="max-w-[720px]">
          <h2 className="text-[30px] font-extrabold leading-[1.08] tracking-[-0.02em] text-white md:text-[44px] lg:text-[50px]">
            What are you waiting for? Start
            <br />
            selling with Facevaly today.
          </h2>
        </div>

        <div className="hidden items-center gap-10 lg:flex xl:gap-16">
          <button
            type="button"
            className="inline-flex h-[46px] min-w-[168px] items-center justify-center rounded-[7px] border-2 border-white bg-transparent px-8 text-[14px] font-bold uppercase tracking-[0.01em] text-white"
          >
            Get Started
          </button>

          <button
            type="button"
            className="inline-flex h-[49px] min-w-[126px] items-center justify-center rounded-[22px] bg-[#f96a0a] px-7 text-[17px] font-extrabold uppercase text-white"
          >
            Sign Up <span className="ml-2 text-[24px] leading-none">↑</span>
          </button>
        </div>

        <div className="flex flex-col gap-3 lg:hidden">
          <button
            type="button"
            className="inline-flex h-[44px] min-w-[150px] items-center justify-center rounded-[7px] border-2 border-white bg-transparent px-6 text-[13px] font-bold uppercase text-white"
          >
            Get Started
          </button>
          <button
            type="button"
            className="inline-flex h-[44px] min-w-[120px] items-center justify-center rounded-[22px] bg-[#f96a0a] px-6 text-[14px] font-extrabold uppercase text-white"
          >
            Sign Up <span className="ml-1 text-[20px] leading-none">↑</span>
          </button>
        </div>
      </div>
    </section>
  );
}
