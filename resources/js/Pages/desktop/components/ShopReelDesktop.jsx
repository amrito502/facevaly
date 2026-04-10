import React, { useCallback, useEffect, useMemo, useRef, useState } from "react";
import {
  ChevronLeft,
  ChevronRight,
  Heart,
  MessageCircle,
  MoreHorizontal,
  Pause,
  Play,
  Volume2,
  VolumeX,
  X,
} from "lucide-react";

const reels = [
  {
    id: 1,
    brand: "Pickly BD",
    title: "3 Layer Kitchen Basket Stand",
    thumbnail: "/frontend/flashsale/1.avif",
    video: "/frontend/reals.mp4",
    logo: "https://cdn-icons-png.flaticon.com/128/3163/3163209.png",
    cta: "Shop Now",
  },
  {
    id: 2,
    brand: "Quick pic",
    title: "Mini Electric Food Warmer",
    thumbnail: "/frontend/flashsale/3.avif",
    video: "/frontend/reals-1.mp4",
    logo: "https://cdn-icons-png.flaticon.com/128/3163/3163209.png",
    cta: "Shop Now",
  },
  {
    id: 3,
    brand: "Deshi Dukan",
    title: "Nivea Soft Rose Care Combo",
    thumbnail: "/frontend/flashsale/2.avif",
    video: "/frontend/reals-3.mp4",
    logo: "https://cdn-icons-png.flaticon.com/128/3163/3163209.png",
    cta: "Shop Now",
  },
  {
    id: 4,
    brand: "Bengali Vibe",
    title: "Prestige Electric Kettle",
    thumbnail: "/frontend/flashsale/1.avif",
    video: "/frontend/reals-4.mp4",
    logo: "https://cdn-icons-png.flaticon.com/128/3163/3163209.png",
    cta: "Shop Now",
  },
  {
    id: 5,
    brand: "Mybuskat",
    title: "Professional Hair Trimmer",
    thumbnail: "/frontend/flashsale/2.avif",
    video: "/frontend/reals.mp4",
    logo: "https://cdn-icons-png.flaticon.com/128/3163/3163209.png",
    cta: "Shop Now",
  },
  {
    id: 6,
    brand: "Venus Leather",
    title: "Handmade Casual Black Shoe",
    thumbnail: "/frontend/flashsale/3.avif",
    video: "/frontend/reals-4.mp4",
    logo: "https://cdn-icons-png.flaticon.com/128/3163/3163209.png",
    cta: "Shop Now",
  },
];

function ReelCard({ item, onClick }) {
  return (
    <button
      type="button"
      onClick={onClick}
      className="group relative h-[260px] w-full overflow-hidden rounded-2xl bg-neutral-950 text-left sm:h-[300px] lg:h-[340px]"
    >
      <img
        src={item.thumbnail}
        alt={item.title}
        className="h-full w-full object-cover transition duration-500 group-hover:scale-[1.04]"
      />

      <div className="absolute inset-0 bg-gradient-to-b from-black/10 via-black/5 to-black/75" />

      <div className="absolute left-3 top-3 flex items-center gap-2 sm:left-4 sm:top-4">
        <div className="flex h-11 w-11 items-center justify-center overflow-hidden rounded-full border-2 border-[#14c8b0] bg-white shadow-lg sm:h-12 sm:w-12">
          <img src={item.logo} alt={item.brand} className="h-full w-full object-cover" />
        </div>
        <div className="rounded-full bg-black/35 px-3 py-1 text-sm font-medium text-white backdrop-blur-md">
          {item.brand}
        </div>
      </div>

      <div className="absolute inset-x-0 bottom-0 p-4 sm:p-5">
        <p className="line-clamp-2 text-base font-semibold text-white sm:text-lg">{item.title}</p>
      </div>
    </button>
  );
}

function StoryViewer({ item, onClose, onNext, onPrev, hasPrev, hasNext }) {
  const videoRef = useRef(null);
  const progressTimer = useRef(null);
  const [isMuted, setIsMuted] = useState(false);
  const [isPlaying, setIsPlaying] = useState(true);
  const [progress, setProgress] = useState(0);

  const clearProgressTimer = useCallback(() => {
    if (progressTimer.current) {
      clearInterval(progressTimer.current);
      progressTimer.current = null;
    }
  }, []);

  const syncPlayState = useCallback(() => {
    const video = videoRef.current;
    if (!video) return;
    setIsPlaying(!video.paused);
  }, []);

  const togglePlay = useCallback(() => {
    const video = videoRef.current;
    if (!video) return;

    if (video.paused) {
      video.play().catch(() => {});
    } else {
      video.pause();
    }
  }, []);

  const toggleMute = useCallback(() => {
    const video = videoRef.current;
    if (!video) return;

    const nextMuted = !video.muted;
    video.muted = nextMuted;
    setIsMuted(nextMuted);
  }, []);

  useEffect(() => {
    const video = videoRef.current;
    if (!video) return;

    video.currentTime = 0;
    video.muted = isMuted;
    video.play().catch(() => {});

    const handleLoaded = () => setProgress(0);
    const handleTimeUpdate = () => {
      if (!video.duration) return;
      setProgress((video.currentTime / video.duration) * 100);
    };
    const handleEnded = () => onNext();
    const handlePlay = () => setIsPlaying(true);
    const handlePause = () => setIsPlaying(false);

    video.addEventListener("loadedmetadata", handleLoaded);
    video.addEventListener("timeupdate", handleTimeUpdate);
    video.addEventListener("ended", handleEnded);
    video.addEventListener("play", handlePlay);
    video.addEventListener("pause", handlePause);

    return () => {
      video.removeEventListener("loadedmetadata", handleLoaded);
      video.removeEventListener("timeupdate", handleTimeUpdate);
      video.removeEventListener("ended", handleEnded);
      video.removeEventListener("play", handlePlay);
      video.removeEventListener("pause", handlePause);
    };
  }, [item.id, isMuted, onNext]);

  useEffect(() => {
    const onKeyDown = (event) => {
      if (event.key === "Escape") onClose();
      if (event.key === "ArrowRight") onNext();
      if (event.key === "ArrowLeft") onPrev();
      if (event.key === " ") {
        event.preventDefault();
        togglePlay();
      }
      if (event.key.toLowerCase() === "m") toggleMute();
    };

    window.addEventListener("keydown", onKeyDown);
    return () => window.removeEventListener("keydown", onKeyDown);
  }, [onClose, onNext, onPrev, toggleMute, togglePlay]);

  useEffect(() => {
    return () => clearProgressTimer();
  }, [clearProgressTimer]);

  return (
    <div className="fixed inset-0 z-[9999] bg-black/95 p-0 sm:p-4">
      <div className="relative mx-auto flex h-full w-full items-center justify-center">
        {hasPrev && (
          <button
            type="button"
            onClick={onPrev}
            className="absolute left-3 top-1/2 z-30 hidden h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-slate-900 shadow-xl transition hover:scale-105 lg:flex"
            aria-label="Previous reel"
          >
            <ChevronLeft className="h-6 w-6" />
          </button>
        )}

        <div className="relative h-full w-full overflow-hidden bg-black sm:h-[92vh] sm:max-h-[860px] sm:w-[420px] sm:rounded-[28px] lg:w-[460px]">
          <video
            ref={videoRef}
            key={item.id}
            src={item.video}
            poster={item.thumbnail}
            playsInline
            className="h-full w-full object-cover"
            controls={false}
          />

          <div className="pointer-events-none absolute inset-0 bg-gradient-to-b from-black/50 via-transparent to-black/80" />

          <div className="absolute inset-x-0 top-0 z-20 p-3 sm:p-4">
            <div className="mb-3 flex items-center gap-1.5">
              <div className="h-1 flex-1 overflow-hidden rounded-full bg-white/20">
                <div className="h-full rounded-full bg-white transition-all duration-150" style={{ width: `${progress}%` }} />
              </div>
            </div>

            <div className="flex items-start justify-between gap-3">
              <div className="flex min-w-0 items-center gap-3">
                <div className="flex h-11 w-11 shrink-0 items-center justify-center overflow-hidden rounded-full border-2 border-[#14c8b0] bg-white">
                  <img src={item.logo} alt={item.brand} className="h-full w-full object-cover" />
                </div>

                <div className="min-w-0 text-white">
                  <div className="flex items-center gap-1.5">
                    <p className="truncate text-sm font-semibold sm:text-base">{item.brand}</p>
                    <ChevronRight className="h-4 w-4 shrink-0" />
                  </div>
                </div>
              </div>

              <div className="flex items-center gap-1 sm:gap-2">
                <button
                  type="button"
                  onClick={togglePlay}
                  className="flex h-10 w-10 items-center justify-center rounded-full bg-black/30 text-white backdrop-blur-md transition hover:bg-black/45"
                  aria-label={isPlaying ? "Pause video" : "Play video"}
                >
                  {isPlaying ? <Pause className="h-4 w-4" /> : <Play className="h-4 w-4" />}
                </button>

                <button
                  type="button"
                  onClick={toggleMute}
                  className="flex h-10 w-10 items-center justify-center rounded-full bg-black/30 text-white backdrop-blur-md transition hover:bg-black/45"
                  aria-label={isMuted ? "Unmute video" : "Mute video"}
                >
                  {isMuted ? <VolumeX className="h-4 w-4" /> : <Volume2 className="h-4 w-4" />}
                </button>

                {/* <button
                  type="button"
                  className="flex h-10 w-10 items-center justify-center rounded-full bg-black/30 text-white backdrop-blur-md transition hover:bg-black/45"
                  aria-label="More options"
                >
                  <MoreHorizontal className="h-4 w-4" />
                </button> */}

                <button
                  type="button"
                  onClick={onClose}
                  className="flex h-10 w-10 items-center justify-center rounded-full bg-black/30 text-white backdrop-blur-md transition hover:bg-black/45"
                  aria-label="Close viewer"
                >
                  <X className="h-4 w-4" />
                </button>
              </div>
            </div>
          </div>

          <div className="absolute inset-x-0 bottom-0 z-20 p-4 sm:p-5">
            <div className="mb-4 rounded-3xl bg-black/18 p-1 backdrop-blur-[1px]">
              <h3 className="px-3 py-2 text-center text-lg font-semibold text-white sm:text-xl">{item.title}</h3>
            </div>

            <button
              type="button"
              className="mb-3 flex h-12 w-full items-center justify-center gap-2 rounded-full bg-[#18c7b0] px-5 text-base font-semibold text-white transition hover:bg-[#11b39e] sm:h-14 sm:text-lg"
            >
              <span>{item.cta}</span>
              <ChevronRight className="h-5 w-5" />
            </button>

            <div className="flex items-center gap-3">
  <form
    onSubmit={(e) => {
      e.preventDefault()
      // ekhane message send korben
    }}
    className="flex flex-1 items-center gap-2 rounded-full border border-white/35 bg-white/10 px-4 backdrop-blur-md transition hover:bg-white/15"
  >
    <MessageCircle className="h-4 w-4 shrink-0 text-white" />

    <input
      type="text"
      placeholder="Send Message"
      className="h-12 flex-1 bg-transparent text-sm text-white placeholder:text-white/70 outline-none"
    />

    <button
      type="submit"
      className="rounded-full bg-white/20 px-4 py-2 text-sm text-white transition hover:bg-white/30"
    >
      Send
    </button>
  </form>

  <button
    type="button"
    className="flex h-12 w-12 shrink-0 items-center justify-center rounded-full border border-white/35 bg-white/10 text-white backdrop-blur-md transition hover:bg-white/15"
    aria-label="Like reel"
  >
    <Heart className="h-5 w-5" />
  </button>
</div>
          </div>
        </div>

        {hasNext && (
          <button
            type="button"
            onClick={onNext}
            className="absolute right-3 top-1/2 z-30 hidden h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-slate-900 shadow-xl transition hover:scale-105 lg:flex"
            aria-label="Next reel"
          >
            <ChevronRight className="h-6 w-6" />
          </button>
        )}
      </div>
    </div>
  );
}

export default function ShopReelResponsive() {
  const [startIndex, setStartIndex] = useState(0);
  const [activeIndex, setActiveIndex] = useState(null);

  const cardsPerView = useMemo(() => {
    if (typeof window === "undefined") return 5;
    if (window.innerWidth >= 1280) return 5;
    if (window.innerWidth >= 1024) return 4;
    if (window.innerWidth >= 640) return 3;
    return 2;
  }, []);

  const [itemsPerView, setItemsPerView] = useState(cardsPerView);

  useEffect(() => {
    const handleResize = () => {
      if (window.innerWidth >= 1280) setItemsPerView(5);
      else if (window.innerWidth >= 1024) setItemsPerView(4);
      else if (window.innerWidth >= 640) setItemsPerView(3);
      else setItemsPerView(2);
    };

    handleResize();
    window.addEventListener("resize", handleResize);
    return () => window.removeEventListener("resize", handleResize);
  }, []);

  const maxIndex = Math.max(reels.length - itemsPerView, 0);

  const visibleReels = useMemo(() => {
    return reels.slice(startIndex, startIndex + itemsPerView);
  }, [startIndex, itemsPerView]);

  const openViewer = (index) => setActiveIndex(index);
  const closeViewer = () => setActiveIndex(null);

  const nextRail = () => {
    setStartIndex((prev) => (prev >= maxIndex ? 0 : prev + 1));
  };

  const prevRail = () => {
    setStartIndex((prev) => (prev <= 0 ? maxIndex : prev - 1));
  };

  const nextViewer = useCallback(() => {
    setActiveIndex((prev) => {
      if (prev === null) return 0;
      return prev === reels.length - 1 ? 0 : prev + 1;
    });
  }, []);

  const prevViewer = useCallback(() => {
    setActiveIndex((prev) => {
      if (prev === null) return 0;
      return prev === 0 ? reels.length - 1 : prev - 1;
    });
  }, []);

  return (
    <section className="mt-6 w-full">
      <div className=" ">
        <div className="mb-4 flex items-center gap-3">
          <div className="flex h-10 w-10 items-center justify-center overflow-hidden rounded-xl bg-slate-100">
            <img src="/images/shopreel.svg" alt="Shop Reel" className="h-full w-full object-cover" />
          </div>
          <h2 className="text-xl font-semibold text-slate-900 sm:text-2xl">Shop Reel</h2>
        </div>

        <div className="relative rounded-3xl bg-white p-3 shadow-sm sm:p-4 lg:p-6">
          {reels.length > itemsPerView && (
            <>
              <button
                type="button"
                onClick={prevRail}
                className="absolute left-2 top-1/2 z-20 hidden h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white text-[#10b9a5] shadow-[0_8px_24px_rgba(15,23,42,0.16)] transition hover:scale-105 md:flex"
                aria-label="Previous reels"
              >
                <ChevronLeft className="h-5 w-5" />
              </button>

              <button
                type="button"
                onClick={nextRail}
                className="absolute right-2 top-1/2 z-20 hidden h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white text-[#10b9a5] shadow-[0_8px_24px_rgba(15,23,42,0.16)] transition hover:scale-105 md:flex"
                aria-label="Next reels"
              >
                <ChevronRight className="h-5 w-5" />
              </button>
            </>
          )}

          <div className={`grid gap-3 sm:gap-4 ${itemsPerView === 5 ? "grid-cols-5" : itemsPerView === 4 ? "grid-cols-4" : itemsPerView === 3 ? "grid-cols-3" : "grid-cols-2"}`}>
            {visibleReels.map((item, idx) => (
              <ReelCard key={item.id} item={item} onClick={() => openViewer(startIndex + idx)} />
            ))}
          </div>

          {reels.length > itemsPerView && (
            <div className="mt-4 flex items-center justify-center gap-2 md:hidden">
              <button
                type="button"
                onClick={prevRail}
                className="flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 text-slate-700"
                aria-label="Previous reels"
              >
                <ChevronLeft className="h-5 w-5" />
              </button>
              <button
                type="button"
                onClick={nextRail}
                className="flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 text-slate-700"
                aria-label="Next reels"
              >
                <ChevronRight className="h-5 w-5" />
              </button>
            </div>
          )}
        </div>
      </div>

      {activeIndex !== null && (
        <StoryViewer
          item={reels[activeIndex]}
          onClose={closeViewer}
          onNext={nextViewer}
          onPrev={prevViewer}
          hasPrev={reels.length > 1}
          hasNext={reels.length > 1}
        />
      )}
    </section>
  );
}
