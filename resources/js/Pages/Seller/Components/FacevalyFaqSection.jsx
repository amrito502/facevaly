import { useState } from "react";
import { ChevronDown } from "lucide-react";

const faqs = [
  {
    id: 1,
    question: "How can I sell on Facevaly?",
    answer:
      "To start selling on Facevaly, visit the Facevaly Seller Center and create a new account using your phone number. Complete the sign-up process by verifying your email, adding your pickup address, and uploading the required documents for verification. Once your store is approved, add your first product, and you're ready to sell! You can also customize your store by adding your logo, cover, and banners through the Store Builder tool.",
  },
  {
    id: 2,
    question: "What categories can I sell on Facevaly?",
    answer: (
      <>
        With multiple categories on Facevaly—ranging from fashion, lifestyle,
        digital goods, FMCG, and lifestyle—you&apos;ll find the perfect fit for your
        products. However, it&apos;s essential to avoid listing counterfeit,
        dangerous, or prohibited items and those restricted by law or cultural
        norms. {""}
        <a href="#" className="text-[#2563eb] hover:underline">
          Click Here to learn more
        </a>
      </>
    ),
  },
  {
    id: 3,
    question: "How much commission does Facevaly charge?",
    answer: (
      <>
        Opening a shop on Facevaly is free! However, a small commission is
        deducted from each order&apos;s payment, with rates varying based on the
        product category. {""}
        <a href="#" className="text-[#2563eb] hover:underline">
          You can find out about commissions in different categories here.
        </a>
      </>
    ),
  },
  {
    id: 4,
    question: "What is the payment policy for Facevaly?",
    answer: (
      <>
        Seller payments are based on orders marked as &apos;Delivered&apos; to the
        customer in the Seller Center. Payments for delivered products are
        settled weekly. In case public holidays/weekends fall upon these dates,
        the payment date will be released on the first upcoming business day.
        You can explore Facevaly&apos;s detailed Payment Policy and find answers to
        frequently asked questions. {""}
        <a href="#" className="text-[#2563eb] hover:underline">
          Click Here to view
        </a>
      </>
    ),
  },
];

export default function FacevalyFaqSection() {
  const [openId, setOpenId] = useState(1);

  return (
    <section className="w-full bg-[#f3f3f3] pt-8 pb-12 md:pt-10 md:pb-16">
      <div className="mx-auto max-w-[1280px] px-6 md:px-10 lg:px-12">
        <h2 className="mb-10 text-[44px] font-medium leading-none text-[#1f3559] md:mb-12 md:text-[52px]">
          FAQs
        </h2>

        <div className="w-full max-w-[1240px]">
          {faqs.map((item) => {
            const isOpen = openId === item.id;

            return (
              <div key={item.id} className="border-b border-[#dddddd]">
                <button
                  type="button"
                  onClick={() => setOpenId(isOpen ? 0 : item.id)}
                  className="flex w-full items-center justify-between gap-6 py-8 text-left md:py-9"
                >
                  <span className="text-[28px] font-bold leading-[1.25] text-[#1f2d3d] md:text-[30px]">
                    {item.question}
                  </span>

                  <ChevronDown
                    className={`h-7 w-7 shrink-0 text-[#6b7280] transition-transform duration-200 ${
                      isOpen ? "rotate-180" : "rotate-0"
                    }`}
                  />
                </button>

                {isOpen && (
                  <div className="pb-8 pr-12 md:pb-9 md:pr-16">
                    <p className="max-w-[1160px] text-[19px] leading-[1.75] text-[#4e5f7b] md:text-[20px]">
                      {item.answer}
                    </p>
                  </div>
                )}
              </div>
            );
          })}
        </div>

        <button
          type="button"
          className="mt-12 inline-flex h-[56px] items-center justify-center rounded-[10px] border border-[#ff6a00] bg-transparent px-9 text-[18px] font-medium text-[#ff6a00] transition-colors duration-200 hover:bg-[#fff7f2]"
        >
          Need more help
        </button>
      </div>
    </section>
  );
}
