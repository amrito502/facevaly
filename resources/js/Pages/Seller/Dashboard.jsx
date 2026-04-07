import React, { useEffect, useRef, useState } from "react";
import {
  LayoutDashboard,
  ShoppingCart,
  Package,
  CreditCard,
  LifeBuoy,
  BarChart3,
  Search,
  ChevronRight,
  Menu,
  Home,
  Sun,
  User,
  LogOut,
  Plus,
  ShoppingBag,
  Wallet,
  Ticket,
  Ruler,
  BadgeDollarSign,
  Receipt,
  Landmark,
  BadgeHelp,
  Hourglass,
  X,
} from "lucide-react";

const menuData = [
  {
    title: "Dashboard",
    icon: LayoutDashboard,
    active: true,
  },
  {
    title: "Order",
    icon: ShoppingCart,
    children: [{ label: "Orders", icon: ShoppingBag }],
  },
  {
    title: "Product",
    icon: Package,
    children: [
      { label: "Products", icon: Package },
      { label: "Add Product", icon: Plus },
      { label: "Product Review", icon: BadgeHelp },
      { label: "Size Guide", icon: Ruler },
    ],
  },
  {
    title: "Payment",
    icon: CreditCard,
    children: [
      { label: "Earning", icon: BadgeDollarSign },
      { label: "Withdraw", icon: Wallet },
      { label: "Payment Report", icon: Receipt },
      { label: "Payment Method", icon: Landmark },
    ],
  },
  {
    title: "Support",
    icon: LifeBuoy,
    children: [{ label: "Support Ticket", icon: Ticket }],
  },
  {
    title: "Data & Advisor",
    icon: BarChart3,
    children: [{ label: "Metrics", icon: BarChart3 }],
  },
];

function SidebarItem({ item, openMenus, toggleMenu, sidebarOpen }) {
  const Icon = item.icon;
  const isOpen = openMenus[item.title];
  const hasChildren = !!item.children?.length;
  const contentRef = useRef(null);
  const [contentHeight, setContentHeight] = useState(0);

  useEffect(() => {
    if (contentRef.current) {
      setContentHeight(contentRef.current.scrollHeight);
    }
  }, [isOpen, sidebarOpen, item.children]);

  return (
    <div className="mb-1.5">
      <button
        onClick={() => hasChildren && toggleMenu(item.title)}
        className={`group flex w-full items-center rounded-xl py-2.5 text-sm transition-all duration-300 ease-out ${
          sidebarOpen ? "justify-between px-3" : "justify-center px-2"
        } ${
          item.active
            ? "bg-orange-50 text-orange-600 shadow-[0_1px_2px_rgba(0,0,0,0.05)]"
            : "text-gray-800 hover:bg-gray-100"
        }`}
      >
        <span className="flex items-center gap-3 font-medium">
          <Icon
            className="h-4 w-4 shrink-0 transition-transform duration-300 ease-out group-hover:scale-110"
            strokeWidth={1.9}
          />
          {sidebarOpen && item.title}
        </span>

        {sidebarOpen && hasChildren && (
          <span
            className="transition-transform duration-300 ease-out"
            style={{ transform: isOpen ? "rotate(90deg)" : "rotate(0deg)" }}
          >
            <ChevronRight className="h-4 w-4" />
          </span>
        )}
      </button>

      {sidebarOpen && hasChildren && (
        <div
          className="overflow-hidden transition-all duration-300 ease-out"
          style={{
            maxHeight: isOpen ? `${contentHeight}px` : "0px",
            opacity: isOpen ? 1 : 0,
            transform: isOpen ? "translateY(0px)" : "translateY(-8px)",
          }}
        >
          <div
            ref={contentRef}
            className="ml-2 mt-1 space-y-1 border-l border-gray-200 pl-4 pt-1 pb-1"
          >
            {item.children.map((child, index) => {
              const ChildIcon = child.icon;
              return (
                <button
                  key={child.label}
                  className="flex w-full items-center gap-2 rounded-lg px-2 py-2 text-left text-sm text-gray-700 transition-all duration-200 ease-out hover:bg-gray-100 hover:translate-x-1"
                  style={{
                    transitionDelay: isOpen ? `${index * 35}ms` : "0ms",
                  }}
                >
                  <ChildIcon className="h-3.5 w-3.5" strokeWidth={1.8} />
                  {child.label}
                </button>
              );
            })}
          </div>
        </div>
      )}
    </div>
  );
}

export default function ShopNiraDashboardClone() {
  const [openMenus, setOpenMenus] = useState({
    Order: false,
    Product: false,
    Payment: false,
    Support: false,
    "Data & Advisor": false,
  });
  const [profileOpen, setProfileOpen] = useState(false);
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [isMobile, setIsMobile] = useState(false);
  const [loggingOut, setLoggingOut] = useState(false);

  const toggleMenu = (title) => {
    setOpenMenus((prev) => ({ ...prev, [title]: !prev[title] }));
  };

  const handleLogout = async () => {
    if (loggingOut) return;

    try {
      setLoggingOut(true);

      const token = document
        .querySelector('meta[name="csrf-token"]')
        ?.getAttribute("content");

      const response = await fetch("/logout", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": token || "",
          Accept: "application/json",
        },
        credentials: "same-origin",
      });

      if (!response.ok) {
        throw new Error("Logout failed");
      }

      window.location.href = "/";
    } catch (error) {
      console.error(error);
      setLoggingOut(false);
      alert("Logout failed. Please try again.");
    }
  };

  useEffect(() => {
    const checkScreen = () => {
      const mobile = window.innerWidth < 768;
      setIsMobile(mobile);
      setSidebarOpen((prev) => {
        if (mobile) return false;
        return prev === false && !mobile ? true : prev;
      });
    };

    checkScreen();
    window.addEventListener("resize", checkScreen);
    return () => window.removeEventListener("resize", checkScreen);
  }, []);

  useEffect(() => {
    const handleClickOutside = () => setProfileOpen(false);

    if (profileOpen) {
      window.addEventListener("click", handleClickOutside);
    }

    return () => window.removeEventListener("click", handleClickOutside);
  }, [profileOpen]);

  const sidebarWidth = isMobile ? "0px" : sidebarOpen ? "235px" : "76px";

  return (
    <div className="min-h-screen bg-[#f3f4f6] text-gray-900">
      {isMobile && sidebarOpen && (
        <div
          onClick={() => setSidebarOpen(false)}
          className="fixed inset-0 z-40 bg-black/30 backdrop-blur-[1px] transition-opacity duration-300"
        />
      )}

      <aside
        className={`fixed left-0 top-0 z-50 h-screen border-r border-gray-200 bg-[#f5f5f5] shadow-[0_1px_2px_rgba(0,0,0,0.05)] transition-all duration-300 ease-out ${
          isMobile
            ? sidebarOpen
              ? "w-[260px] translate-x-0"
              : "w-[260px] -translate-x-full"
            : sidebarOpen
            ? "w-[235px] translate-x-0"
            : "w-[76px] translate-x-0"
        }`}
      >
        <div
          className={`flex items-center px-4 py-4 ${
            sidebarOpen ? "justify-between" : "justify-center"
          }`}
        >
          <div className={`flex items-center ${sidebarOpen ? "gap-3" : "justify-center"}`}>
            <div className="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-orange-500 text-white shadow-[0_1px_2px_rgba(0,0,0,0.05)] transition-transform duration-300 hover:scale-105">
              <span className="text-lg font-bold">C</span>
            </div>
            {sidebarOpen && (
              <h1 className="text-[18px] font-bold text-orange-600">ShopNira</h1>
            )}
          </div>

          {isMobile && sidebarOpen && (
            <button
              onClick={() => setSidebarOpen(false)}
              className="rounded-lg p-2 transition-colors duration-200 hover:bg-gray-100"
            >
              <X className="h-5 w-5" />
            </button>
          )}
        </div>

        {sidebarOpen && (
          <div className="px-4 pb-4">
            <div className="relative">
              <Search className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" />
              <input
                type="text"
                placeholder="Search.."
                className="h-11 w-full rounded-xl border border-gray-200 bg-white pl-10 pr-4 text-sm shadow-[0_1px_2px_rgba(0,0,0,0.05)] outline-none transition-all duration-300 focus:border-orange-400 focus:shadow-md"
              />
            </div>
          </div>
        )}

        <nav className="px-2 pb-8">
          {menuData.map((item) => (
            <SidebarItem
              key={item.title}
              item={item}
              openMenus={openMenus}
              toggleMenu={toggleMenu}
              sidebarOpen={sidebarOpen}
            />
          ))}
        </nav>
      </aside>

      <header
        className="fixed top-0 z-30 flex h-16 items-center justify-between border-b border-gray-200 bg-[#fafafa] px-4 shadow-[0_1px_2px_rgba(0,0,0,0.05)] transition-all duration-300 ease-out"
        style={{
          left: isMobile ? "0px" : sidebarWidth,
          width: isMobile ? "100%" : `calc(100% - ${sidebarWidth})`,
        }}
      >
        <div className="flex items-center gap-3 sm:gap-4">
          <button
            onClick={() => setSidebarOpen((v) => !v)}
            className="rounded-lg p-2 transition-all duration-200 hover:bg-gray-100 active:scale-95"
          >
            <Menu className="h-5 w-5" />
          </button>
          <div className="flex items-center gap-2 text-sm font-semibold sm:gap-3">
            <Home className="h-4 w-4" />
            <span>Dashboard</span>
          </div>
        </div>

        <div className="relative flex items-center gap-2 sm:gap-3">
          <button className="rounded-xl border border-gray-200 bg-white p-2 shadow-[0_1px_2px_rgba(0,0,0,0.05)] transition-all duration-200 hover:bg-gray-50 hover:shadow-md active:scale-95">
            <Sun className="h-4 w-4" />
          </button>

          <button
            onClick={(e) => {
              e.stopPropagation();
              setProfileOpen((v) => !v);
            }}
            className="flex h-8 w-8 items-center justify-center rounded-full bg-gray-200 transition-all duration-200 hover:bg-gray-300 active:scale-95"
          >
            <User className="h-4 w-4 text-gray-600" />
          </button>

          <div
            onClick={(e) => e.stopPropagation()}
            className="absolute right-0 top-12 z-20 w-36 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-[0_10px_30px_rgba(0,0,0,0.08)] transition-all duration-200 ease-out"
            style={{
              opacity: profileOpen ? 1 : 0,
              transform: profileOpen ? "translateY(0px) scale(1)" : "translateY(-8px) scale(0.98)",
              pointerEvents: profileOpen ? "auto" : "none",
            }}
          >
            <div className="border-b border-gray-100 px-4 py-3 text-sm font-semibold">
              ShopNira
            </div>
            <button className="flex w-full items-center gap-2 px-4 py-3 text-sm transition-colors duration-200 hover:bg-gray-50">
              <User className="h-4 w-4 text-gray-500" />
              Profile
            </button>
            <button
              onClick={handleLogout}
              disabled={loggingOut}
              className="flex w-full items-center gap-2 px-4 py-3 text-sm transition-colors duration-200 hover:bg-gray-50 disabled:opacity-60"
            >
              <LogOut className="h-4 w-4 text-gray-500" />
              {loggingOut ? "Logging out..." : "Log Out"}
            </button>
          </div>
        </div>
      </header>

      <main
        className="min-h-screen p-4 pt-24 transition-all duration-300 ease-out sm:p-6 sm:pt-24"
        style={{ marginLeft: isMobile ? "0px" : sidebarWidth }}
      >
        <section className="flex min-h-[calc(100vh-96px)] items-center justify-center">
          <div className="w-full max-w-[420px] rounded-3xl border border-gray-200 bg-white shadow-[0_10px_30px_rgba(0,0,0,0.08)] transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_16px_40px_rgba(0,0,0,0.10)]">
            <div className="flex flex-col items-center px-5 py-8 text-center sm:px-8 sm:py-10">
              <div className="mb-4 text-gray-500">
                <Hourglass className="h-10 w-10 sm:h-12 sm:w-12" strokeWidth={1.5} />
              </div>
              <h2 className="mb-3 text-base font-semibold text-gray-900 sm:text-[18px]">
                Wait for the Account Activation
              </h2>
              <p className="mb-5 max-w-[290px] text-sm leading-7 text-gray-500 sm:text-[15px]">
                Your account is under review. Please wait for activation by
                <span className="font-semibold text-gray-800"> Govaly Authority.</span>
              </p>
              <button
                onClick={handleLogout}
                disabled={loggingOut}
                className="inline-flex items-center rounded-xl bg-orange-500 px-6 py-2.5 text-sm font-medium text-white transition-all duration-200 hover:bg-orange-600 hover:shadow-lg active:scale-95 disabled:opacity-60"
              >
                <span>{loggingOut ? "Logging out..." : "Logout"}</span>
                <LogOut className="ml-2 h-4 w-4" />
              </button>
            </div>
          </div>
        </section>
      </main>
    </div>
  );
}