import { useState } from "react";
import { ChevronDown, Clock3 } from "lucide-react";

const steps = [
  {
    id: 1,
    title: "Sign up with a phone number",
    time: "2 mins",
    content: (
      <>
        If you have an existing seller center account, you can log in directly to
        Facevaly seller center to continue with onboarding. {""}
        <a href="#" className="text-[#2563eb] hover:underline">
          Log in
        </a>
      </>
    ),
  },
  {
    id: 2,
    title: "Fill in contact email & address details",
    time: "1 mins",
    content:
      "Fill in your contact email to receive important updates from us. Ensure pickup and return warehouse addresses are keyed in accurately for a smooth delivery process.",
  },
  {
    id: 3,
    title: "Submit ID and Bank Account details",
    time: "5 mins",
    content: (
      <>
        <p className="mb-2">
          For account verification, the following documents are required.
        </p>
        <ul className="mb-2 list-disc pl-5">
          <li>Individual Sellers - Identity Card and Bank document</li>
          <li>Corporate Sellers - Business Registration and Bank document</li>
        </ul>
        <p>
          Note: Marketplace sellers can now use SignPass to verify their account
          quicker without identity card / Business registration document. Bank
          document is still required.
        </p>
      </>
    ),
  },
  {
    id: 4,
    title: "Upload products and get orders!",
    time: "10 mins",
    content:
      "Highly recommend for you to upload at least 10 products to sell! The more assortment you have, the better it will be for customer's shopping experience!",
  },
];

export default function FacevalyStepsSection() {
  const [openId, setOpenId] = useState(1);

  return (
    <section className="w-full bg-[#f6efec] py-14 md:py-20">
      <div className="mx-auto max-w-[1280px] px-4 md:px-8 lg:px-10">
        <h2 className="mb-12 text-center text-[34px] font-bold leading-none text-[#1f3559] md:mb-16 md:text-[46px]">
          Steps to Start Selling
        </h2>

        <div className="grid grid-cols-1 gap-10 lg:grid-cols-[0.95fr_1.05fr] lg:gap-16 xl:gap-20">
          <div className="max-w-[520px] pt-1 md:pt-3 lg:pl-2">
            <p className="max-w-[500px] text-[20px] leading-[1.55] text-[#142c52] md:text-[21px]">
              Sign up now to be a Facevaly Seller! Facevaly offers good
              opportunity and support for you to dive into the market and grow
              your customer base with ease. As a Facevaly Seller, you will get
              access to various resources to help you drive your business on our
              platform.
            </p>

            <button
              type="button"
              className="mt-10 inline-flex h-[56px] items-center justify-center rounded-[8px] bg-[#2f73f5] px-8 text-[19px] font-semibold text-white transition-opacity duration-200 hover:opacity-90"
            >
              Sign up now
            </button>
          </div>

          <div className="space-y-4 md:space-y-5">
            {steps.map((step) => {
              const isOpen = openId === step.id;

              return (
                <div
                  key={step.id}
                  className="overflow-hidden rounded-[10px] bg-[#f8f8f8]"
                >
                  <button
                    type="button"
                    onClick={() => setOpenId(isOpen ? 0 : step.id)}
                    className="flex w-full items-center justify-between gap-4 px-5 py-6 text-left md:px-6 md:py-7"
                  >
                    <div className="flex items-center gap-4 pr-3">
                      <span className="text-[24px] font-bold leading-none text-[#1a2f52] md:text-[25px]">
                        {step.id}.
                      </span>
                      <span className="text-[24px] font-bold leading-[1.2] text-[#1a2f52] md:text-[26px]">
                        {step.title}
                      </span>
                    </div>

                    <ChevronDown
                      className={`h-6 w-6 shrink-0 text-[#8b93a1] transition-transform duration-200 ${
                        isOpen ? "rotate-180" : "rotate-0"
                      }`}
                    />
                  </button>

                  {isOpen && (
                    <div className="px-5 pb-6 md:px-6 md:pb-7">
                      <div className="ml-[42px] md:ml-[46px]">
                        <div className="max-w-[610px] text-[15px] leading-[1.65] text-[#5d6777] md:text-[16px]">
                          {step.content}
                        </div>

                        <div className="mt-5 inline-flex items-center gap-1 rounded-[4px] bg-[#edf1f5] px-2.5 py-1.5 text-[14px] font-medium text-[#6b7280]">
                          <Clock3 className="h-4 w-4" />
                          <span>{step.time}</span>
                        </div>
                      </div>
                    </div>
                  )}
                </div>
              );
            })}
          </div>
        </div>
      </div>
    </section>
  );
}
