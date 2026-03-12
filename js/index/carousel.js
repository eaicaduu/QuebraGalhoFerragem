document.addEventListener("DOMContentLoaded", () => {
  const carousel = document.getElementById("carouselCards");
  let startX = 0;

  carousel.addEventListener("touchstart", (e) => {
    startX = e.touches[0].clientX;
  });

  carousel.addEventListener("touchend", (e) => {
    const endX = e.changedTouches[0].clientX;
    const diff = startX - endX;

    if (Math.abs(diff) > 50) {
      const direction = diff > 0 ? "next" : "prev";
      const bsCarousel =
        bootstrap.Carousel.getInstance(carousel) ||
        new bootstrap.Carousel(carousel);
      bsCarousel[direction]();
    }
  });
});
