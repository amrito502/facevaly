import React, { useMemo, useState, useEffect } from "react";
import "bootstrap-icons/font/bootstrap-icons.css";
import { Toaster } from "react-hot-toast";

export default function AppLayout({ children }) {
  const [mobileSidebarOpen, setMobileSidebarOpen] = useState(false);
  const [openMenus, setOpenMenus] = useState({});

  const [showAppPopup, setShowAppPopup] = useState(false);
  const [showHelpPopup, setShowHelpPopup] = useState(false);
  const [showLangPopup, setShowLangPopup] = useState(false);

  const [showLoginModal, setShowLoginModal] = useState(false);
  const [showSignupModal, setShowSignupModal] = useState(false);

  const [mobileCompactHeader, setMobileCompactHeader] = useState(false);

  const menuData = useMemo(
    () => [
      {
        title: "For You",
        children: [
          "Baby Bath",
          "Baby Accessories",
          "Baby Clothing Set",
          "Baby Cream",
          "Baby Diapers",
          "Baby Lotion",
          "Baby Oil",
          "Baby Powder",
          "Baby Shoes",
        ],
      },
      {
        title: "Men",
        children: [
          {
            title: "Men Fashion Accessories",
            children: [
              "See All",
              "Men Bags",
              "Men Belts",
              "Men Caps & Hats",
              "Men Jewellery",
              "Men Sunglasses",
              "Men Wallet",
              "Men Watch",
            ],
          },
          {
            title: "Men Bottomwear",
            children: ["Jeans", "Trousers", "Shorts", "Track Pants"],
          },
          {
            title: "Men Footwear",
            children: ["Sneakers", "Sandals", "Slippers", "Boots"],
          },
          {
            title: "Men Topwear",
            children: ["T-Shirt", "Shirt", "Polo", "Punjabi"],
          },
        ],
      },
      {
        title: "Women",
        children: [
          {
            title: "Women Fashion",
            children: ["See All", "Tops", "Kurtis", "Sarees", "Bags"],
          },
          {
            title: "Women Footwear",
            children: ["Heels", "Flats", "Sandals"],
          },
        ],
      },
      {
        title: "Kids",
        children: ["Boys Fashion", "Girls Fashion", "Toys", "School Items"],
      },
      {
        title: "Baby",
        children: ["Diapers", "Feeding", "Baby Care", "Baby Toys"],
      },
      {
        title: "Health & Beauty",
        children: ["Skin Care", "Hair Care", "Makeup", "Personal Care"],
      },
    ],
    []
  );

  const toggleMenu = (key) => {
    setOpenMenus((prev) => ({
      ...prev,
      [key]: !prev[key],
    }));
  };

  const closeAllPopups = () => {
    setShowAppPopup(false);
    setShowHelpPopup(false);
    setShowLangPopup(false);
  };

  useEffect(() => {
    if (mobileSidebarOpen || showLoginModal || showSignupModal) {
      document.body.classList.add("sidebar-open");
    } else {
      document.body.classList.remove("sidebar-open");
    }

    return () => document.body.classList.remove("sidebar-open");
  }, [mobileSidebarOpen, showLoginModal, showSignupModal]);

  useEffect(() => {
    const handleScroll = () => {
      if (window.innerWidth <= 991) {
        setMobileCompactHeader(window.scrollY > 20);
      }
    };

    window.addEventListener("scroll", handleScroll);
    handleScroll();

    return () => window.removeEventListener("scroll", handleScroll);
  }, []);

  return (
    <>
    <Toaster position="top-right" />
      <div className="app-layout-shell">
        {/* Desktop Fixed Header */}
        <div className="desktop-only desktop-fixed-header-wrap">
          <div className="desktop-topbar">
            <div className="app-container desktop-topbar-inner">
              <div
                className="topbar-item has-popup"
                onMouseEnter={() => {
                  closeAllPopups();
                  setShowAppPopup(true);
                }}
                onMouseLeave={() => setShowAppPopup(false)}
              >
                <button type="button" className="topbar-link">
                  SAVE MORE ON APP
                </button>

                {showAppPopup && (
                  <div className="header-popup popup-app">
                    <div className="popup-arrow"></div>
                    <h4>Download the App</h4>

                    <div className="qr-box">
                      <img src="/images/qr-code.png" alt="QR Code" />
                    </div>

                    <div className="store-buttons">
                      <a href="#" className="store-btn black">
                        <i className="bi bi-apple"></i>
                        <span>App Store</span>
                      </a>
                      <a href="#" className="store-btn black">
                        <i className="bi bi-google-play"></i>
                        <span>Google Play</span>
                      </a>
                    </div>
                  </div>
                )}
              </div>

              <a href="#" className="topbar-link">BECOME A SELLER</a>

              <div
                className="topbar-item has-popup"
                onMouseEnter={() => {
                  closeAllPopups();
                  setShowHelpPopup(true);
                }}
                onMouseLeave={() => setShowHelpPopup(false)}
              >
                <button type="button" className="topbar-link">
                  HELP & SUPPORT
                </button>

                {showHelpPopup && (
                  <div className="header-popup popup-help">
                    <div className="popup-arrow"></div>

                    <a href="#" className="help-item">
                      <i className="bi bi-person-badge"></i>
                      <span>Help Center</span>
                    </a>
                    <a href="#" className="help-item">
                      <i className="bi bi-headset"></i>
                      <span>Contact Customer Care</span>
                    </a>
                    <a href="#" className="help-item">
                      <i className="bi bi-truck"></i>
                      <span>Shipping & Delivery</span>
                    </a>
                    <a href="#" className="help-item">
                      <i className="bi bi-box-seam"></i>
                      <span>Order</span>
                    </a>
                    <a href="#" className="help-item">
                      <i className="bi bi-credit-card"></i>
                      <span>Payment</span>
                    </a>
                    <a href="#" className="help-item">
                      <i className="bi bi-currency-dollar"></i>
                      <span>Returns & Refunds</span>
                    </a>
                  </div>
                )}
              </div>

              <button
                type="button"
                className="topbar-link"
                onClick={() => {
                  closeAllPopups();
                  setShowLoginModal(true);
                }}
              >
                LOGIN
              </button>

              <button
                type="button"
                className="topbar-link"
                onClick={() => {
                  closeAllPopups();
                  setShowSignupModal(true);
                }}
              >
                SIGN UP
              </button>

              <div
                className="topbar-item has-popup"
                onMouseEnter={() => {
                  closeAllPopups();
                  setShowLangPopup(true);
                }}
                onMouseLeave={() => setShowLangPopup(false)}
              >
                <button type="button" className="topbar-link">
                  ভাষা
                </button>

                {showLangPopup && (
                  <div className="header-popup popup-lang">
                    <div className="popup-arrow"></div>

                    <button type="button" className="lang-item">
                      <span className="lang-left">
                        <span className="lang-dot"></span>
                        <span>BN / Bengali</span>
                      </span>
                    </button>

                    <button type="button" className="lang-item active">
                      <span className="lang-left">
                        <span className="lang-flag">🇬🇧</span>
                        <span>EN / English</span>
                      </span>
                      <i className="bi bi-check-lg"></i>
                    </button>
                  </div>
                )}
              </div>
            </div>
          </div>

          <header className="desktop-header">
            <div className="app-container desktop-header-inner">
              <a href="#" className="desktop-brand">
                <img src="/frontend/logo.png" alt="Facevaly Logo" className="brand-logo-img" />
                <span className="brand-text">Facevaly</span>
              </a>

              <div className="desktop-search">
                <input type="text" placeholder="Search in Facevaly" />
                <button type="button" aria-label="Search">
                  <i className="bi bi-search"></i>
                </button>
              </div>

              <button className="desktop-cart-btn" type="button" aria-label="Cart">
                <i className="bi bi-cart3"></i>
              </button>
            </div>
          </header>
        </div>

        {/* Mobile Header */}
        <div className={`mobile-only mobile-header ${mobileCompactHeader ? "compact" : ""}`}>
          <div className="mobile-header-inner">
            <div className="mobile-app-download-bar">
              <div className="mobile-app-left">
                <div className="mobile-app-logo-box">
                  <img src="/frontend/logo.png" alt="Facevaly Logo" className="mobile-app-logo" />
                </div>

                <div className="mobile-app-text">
                  <h4>Facevaly App</h4>
                  <p>Save more on App</p>
                </div>
              </div>

              <button className="mobile-open-btn" type="button">
                Open
              </button>
            </div>

            <div className="mobile-search-section">
              <div className="mobile-search-box">
                <i className="bi bi-search mobile-search-icon"></i>
                <input type="text" placeholder="t shirt" />
                <button type="button">Search</button>
              </div>
            </div>
          </div>
        </div>

        {/* Main */}
        <main className="main-content-area">
          <div className="app-container">{children}</div>
        </main>

        {/* Desktop Footer */}
        <footer className="desktop-only desktop-footer">
          <div className="app-container footer-grid">
            <div className="footer-brand-col">
              <div className="footer-brand">
                <div className="footer-logo-box">
                  <img src="/frontend/logo.png" alt="Facevaly Logo" className="footer-logo-img" />
                </div>

                <div>
                  <h3>Facevaly</h3>
                  <p>Bangladesh's Favorite Online Fashion Mall</p>
                  <small>BID - 751626035</small>
                </div>
              </div>
            </div>

            <div className="footer-links-col">
              <h4>Facevaly Policies</h4>
              <a href="#">Return & Refund Policy</a>
              <a href="#">Exchange Policy</a>
              <a href="#">Shipping & Delivery Policy</a>
              <a href="#">Cancellation Policy</a>
              <a href="#">Privacy Policy</a>
              <a href="#">Terms & Conditions</a>
            </div>

            <div className="footer-links-col">
              <h4>Facevaly Seller</h4>
              <a href="#">Become A Seller</a>
              <a href="#">Seller Policy</a>
              <a href="#">Product Policy</a>
              <a href="#">Pickup & Delivery Policy</a>
              <a href="#">Seller Exchange & Return Policy</a>
            </div>

            <div className="footer-links-col">
              <h4>Social Links</h4>
              <a href="#"><i className="bi bi-facebook"></i> Facebook</a>
              <a href="#"><i className="bi bi-instagram"></i> Instagram</a>
              <a href="#"><i className="bi bi-tiktok"></i> TikTok</a>
              <a href="#"><i className="bi bi-youtube"></i> YouTube</a>
              <a href="#"><i className="bi bi-whatsapp"></i> WhatsApp</a>
            </div>
          </div>

          <div className="footer-copy">© Facevaly Limited</div>
        </footer>

        {/* Mobile Bottom Nav */}
        <div className="  mobile-bottom-nav">
          <button className="mobile-nav-item active" type="button">
            <i className="bi bi-house-door"></i>
            <span>Home</span>
          </button>

          <button
            className="mobile-nav-item"
            type="button"
            onClick={() => setMobileSidebarOpen(true)}
          >
            <i className="bi bi-grid"></i>
            <span>Category</span>
          </button>

          <button className="mobile-nav-item" type="button">
            <i className="bi bi-cart3"></i>
            <span>Cart</span>
          </button>

          <button className="mobile-nav-item" type="button">
            <i className="bi bi-chat-dots"></i>
            <span>Live Chat</span>
          </button>

          <button className="mobile-nav-item" type="button">
            <i className="bi bi-person"></i>
            <span>Profile</span>
          </button>
        </div>
      </div>

      <div
        className={`mobile-sidebar-overlay ${mobileSidebarOpen ? "show" : ""}`}
        onClick={() => setMobileSidebarOpen(false)}
      ></div>

      <aside className={`mobile-sidebar-drawer ${mobileSidebarOpen ? "show" : ""}`}>
        <div className="mobile-sidebar-header">
          <h3>Categories</h3>
          <button type="button" onClick={() => setMobileSidebarOpen(false)}>
            <i className="bi bi-x-lg"></i>
          </button>
        </div>

        <div className="mobile-sidebar-body">
          {menuData.map((item) => (
            <AccordionItem
              key={item.title}
              item={item}
              openMenus={openMenus}
              toggleMenu={toggleMenu}
              level={0}
            />
          ))}
        </div>

        <div className="mobile-sidebar-footer">
          <a href="#">
            <span>
              <i className="bi bi-headset"></i>
              Facevaly Helpline
            </span>
            <i className="bi bi-box-arrow-up-right"></i>
          </a>

          <a href="#">
            <span>
              <i className="bi bi-envelope"></i>
              support@facevaly.com.bd
            </span>
            <i className="bi bi-box-arrow-up-right"></i>
          </a>

          <a href="#">
            <span>
              <i className="bi bi-telephone"></i>
              01969901212
            </span>
            <i className="bi bi-box-arrow-up-right"></i>
          </a>

          <a href="#">
            <span>
              <i className="bi bi-whatsapp"></i>
              01907104920
            </span>
            <i className="bi bi-box-arrow-up-right"></i>
          </a>
        </div>
      </aside>

      {(showLoginModal || showSignupModal) && (
        <div
          className="modal-overlay"
          onClick={() => {
            setShowLoginModal(false);
            setShowSignupModal(false);
          }}
        ></div>
      )}

      {showLoginModal && (
        <div className="auth-modal auth-login-modal">
          <button
            className="modal-close-btn"
            type="button"
            onClick={() => setShowLoginModal(false)}
          >
            <i className="bi bi-x-lg"></i>
          </button>

          <div className="login-tab-head">
            <button className="active" type="button">Password</button>
            <button type="button">Phone Number</button>
          </div>

          <div className="auth-form">
            <input type="text" placeholder="Please enter your Phone or Email" />
            <div className="password-input-wrap">
              <input type="password" placeholder="Please enter your password" />
              <i className="bi bi-eye-slash"></i>
            </div>

            <div className="forgot-row">
              <a href="#">Forgot password?</a>
            </div>

            <button className="auth-primary-btn" type="button">
              LOGIN
            </button>

            <p className="auth-switch-text">
              Don't have an account?{" "}
              <button
                type="button"
                className="inline-link-btn"
                onClick={() => {
                  setShowLoginModal(false);
                  setShowSignupModal(true);
                }}
              >
                Sign up
              </button>
            </p>

            <div className="auth-social-title">Or, login with</div>

            <div className="social-login-row">
              <button type="button" className="social-btn google">
                <i className="bi bi-google"></i>
                <span>Google</span>
              </button>
              <button type="button" className="social-btn facebook">
                <i className="bi bi-facebook"></i>
                <span>Facebook</span>
              </button>
            </div>
          </div>
        </div>
      )}

      {showSignupModal && (
        <div className="auth-modal auth-signup-modal">
          <button
            className="modal-close-btn"
            type="button"
            onClick={() => setShowSignupModal(false)}
          >
            <i className="bi bi-x-lg"></i>
          </button>

          <h3 className="signup-title">Sign up</h3>

          <div className="signup-phone-row">
            <input type="text" value="BD +880" readOnly className="phone-code-input" />
            <input type="text" placeholder="Please enter your phone number" />
          </div>

          <label className="terms-check-row">
            <input type="checkbox" />
            <span>
              By creating and/or using your account, you agree to our{" "}
              <a href="#">Terms of Use</a> and <a href="#">Privacy Policy</a>.
            </span>
          </label>

          <button className="auth-primary-btn" type="button">
            <i className="bi bi-phone"></i>
            <span>Send code via SMS</span>
          </button>

          <p className="auth-switch-text">
            Already have an account?{" "}
            <button
              type="button"
              className="inline-link-btn"
              onClick={() => {
                setShowSignupModal(false);
                setShowLoginModal(true);
              }}
            >
              Log in Now
            </button>
          </p>

          <div className="auth-social-title">Or, sign up with</div>

          <div className="social-login-row">
            <button type="button" className="social-btn google">
              <i className="bi bi-google"></i>
              <span>Google</span>
            </button>
            <button type="button" className="social-btn facebook">
              <i className="bi bi-facebook"></i>
              <span>Facebook</span>
            </button>
          </div>
        </div>
      )}
    </>
  );
}

function AccordionItem({ item, openMenus, toggleMenu, level = 0 }) {
  const isOpen = !!openMenus[item.title];
  const hasChildren = Array.isArray(item.children) && item.children.length > 0;
  const hasNestedObjects =
    hasChildren && item.children.some((child) => typeof child === "object");

  return (
    <div className={`accordion-item-custom level-${level}`}>
      <button
        type="button"
        className={`accordion-trigger level-${level}`}
        onClick={() => hasChildren && toggleMenu(item.title)}
      >
        <span>{item.title}</span>
        {hasChildren ? (
          <i className={`bi ${isOpen ? "bi-chevron-up" : "bi-chevron-down"}`}></i>
        ) : null}
      </button>

      {hasChildren && (
        <div className={`accordion-content ${isOpen ? "open" : ""}`}>
          <div className="accordion-content-inner">
            {hasNestedObjects
              ? item.children.map((child) =>
                  typeof child === "object" ? (
                    <AccordionItem
                      key={child.title}
                      item={child}
                      openMenus={openMenus}
                      toggleMenu={toggleMenu}
                      level={level + 1}
                    />
                  ) : (
                    <a href="#" key={child} className={`menu-link level-${level + 1}`}>
                      {child}
                    </a>
                  )
                )
              : item.children.map((child) => (
                  <a href="#" key={child} className={`menu-link level-${level + 1}`}>
                    {child === "See All" ? <em>{child}</em> : child}
                  </a>
                ))}
          </div>
        </div>
      )}
    </div>
  );
}