export default function SellingStepsFlow() {
  return (
    <section className="w-full bg-[#F8EFF1] px-4 py-8 sm:px-6 lg:px-12">
      <div className="mx-auto max-w-[1500px]">
        <h2 style={{ color: "#525252", fontSize: "38px" }}  className="mb-8 text-3xl font-bold text-black sm:text-4xl lg:text-5xl">
          4 Simple Steps to Start Selling
        </h2>

        <img
          loading="lazy"
          className="mx-auto w-full h-auto max-h-[700px] object-contain"
          src="/frontend/step1.png"
          alt="4 Simple Steps to Start Selling"
        />
      </div>
    </section>
  );
}