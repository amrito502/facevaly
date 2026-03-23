export default function FacevalyTopBar() {
  return (
    <header className="fixed top-0 left-0 z-50 w-full border-t border-[#262626] bg-white">
      <div className="mx-auto flex h-[64px] max-w-[1920px] items-center justify-between px-6 md:px-10 lg:px-16 xl:px-20">
        <div className="flex items-center">
          <div className="flex items-center gap-3">
            <div className="relative h-[38px] w-[24px]">
              <span className="absolute left-0 top-0 h-[18px] w-[11px] rounded-[2px] bg-[#ff6a00]" />
              <span className="absolute bottom-0 left-0 h-[18px] w-[11px] rounded-[2px] bg-[#ff6a00]" />
              <span className="absolute right-0 top-[8px] h-[22px] w-[11px] rounded-[2px] bg-[#ff6a00]" />
            </div>

            <div className="leading-none text-[#ff6a00]">
              <div className="text-[20px] font-extrabold tracking-[-0.02em]">Facevaly</div>
              <div className="mt-[2px] text-[13px] font-bold tracking-[0.02em]">
                Seller Center
              </div>
            </div>
          </div>
        </div>

        <div className="flex items-center gap-8 text-[15px] font-medium text-[#5f6980] md:gap-10">
          <button type="button" className="flex items-center gap-3">
            <span className="relative inline-flex h-[24px] w-[24px] items-center justify-center overflow-hidden rounded-full bg-[#1f8f2f]">
              <span className="absolute h-[12px] w-[12px] rounded-full bg-[#d31729]" />
            </span>
            <span>Bangladesh</span>
            <span className="text-[#ff6a00]">⌄</span>
          </button>

          <button type="button" className="flex items-center gap-2">
            <span>English</span>
            <span className="text-[#ff6a00]">⌄</span>
          </button>
        </div>
      </div>
    </header>
  );
}