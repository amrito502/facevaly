export default function FeatureCardsSection() {
  const items = [
    {
      icon: RegistrationIcon,
      title: "Free Registration",
      desc: "Account registration & listing items for sale is free",
    },
    {
      icon: MarketingIcon,
      title: "Free Marketing",
      desc: "We have no extra charge for marketing and sales except commission",
    },
    {
      icon: PaymentIcon,
      title: "On Time Payment",
      desc: "Get on time payment withdraw through Bkash or Bank Account",
    },
    {
      icon: EngagementIcon,
      title: "Reach & Engagement",
      desc: "Get 100X reach and engagement of your brand and collections",
    },
    {
      icon: ShippingIcon,
      title: "Hustle Free Shipping",
      desc: "We handle the entire Delivery and Logistics for your order and deliver it to your customer doorstep.",
    },
    {
      icon: SupportIcon,
      title: "24/7 Support",
      desc: "Get response within 2 minute. Dedicated Key Account Manager and Our support team is always for your support.",
    },
  ];

  return (
    <section className="w-full bg-[#f7edf3] py-6 md:py-8">
      <div className="mx-auto max-w-[1540px] px-4 md:px-6">
        <div className="grid grid-cols-1 gap-5 md:grid-cols-2 md:gap-5">
          {items.map((item, index) => {
            const Icon = item.icon;
            return (
              <div
                key={index}
                className="rounded-[10px] bg-[#f3f3f3] px-5 py-6 shadow-none transition-all duration-200"
              >
                <div className="mb-4 text-[#111111]">
                  <Icon />
                </div>

                <h3 className="mb-2 text-[17px] font-semibold leading-none text-[#F85606] md:text-[18px]">
                  {item.title}
                </h3>

                <p className="max-w-[640px] text-[15px] leading-[1.45] text-[#111111] md:text-[17px]">
                  {item.desc}
                </p>
              </div>
            );
          })}
        </div>
      </div>
    </section>
  );
}

function IconBase({ children }) {
  return (
    <svg
      width="24"
      height="24"
      viewBox="0 0 24 24"
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
      className="h-6 w-6"
      stroke="currentColor"
      strokeWidth="2"
      strokeLinecap="round"
      strokeLinejoin="round"
    >
      {children}
    </svg>
  );
}

function RegistrationIcon() {
  return (
    <IconBase>
      <path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z" />
      <path d="M14 3v5h5" />
      <path d="M9 13h6" />
    </IconBase>
  );
}

function MarketingIcon() {
  return (
    <IconBase>
      <path d="M3 11v2" />
      <path d="M6 9v6" />
      <path d="M9 7v10" />
      <path d="M13 5l8 3v8l-8 3z" />
    </IconBase>
  );
}

function PaymentIcon() {
  return (
    <IconBase>
      <rect x="3" y="6" width="15" height="12" rx="2" />
      <path d="M18 10l3-3" />
      <path d="M18 10l3 3" />
      <path d="M6 10h5" />
    </IconBase>
  );
}

function EngagementIcon() {
  return (
    <IconBase>
      <circle cx="6" cy="12" r="2" />
      <circle cx="18" cy="6" r="2" />
      <circle cx="18" cy="18" r="2" />
      <path d="M8 11l8-4" />
      <path d="M8 13l8 4" />
    </IconBase>
  );
}

function ShippingIcon() {
  return (
    <IconBase>
      <path d="M12 2l7 4v8l-7 4-7-4V6z" />
      <path d="M5 6l7 4 7-4" />
      <path d="M12 10v8" />
    </IconBase>
  );
}

function SupportIcon() {
  return (
    <IconBase>
      <path d="M4 12a8 8 0 0 1 16 0" />
      <rect x="3" y="12" width="4" height="7" rx="2" />
      <rect x="17" y="12" width="4" height="7" rx="2" />
      <path d="M12 19v2" />
    </IconBase>
  );
}