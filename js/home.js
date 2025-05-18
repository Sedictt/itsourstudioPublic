document.addEventListener('DOMContentLoaded', function () {
  const autoScrollSpeed = 3000; // ms between slides
  let currentIndex = 0;
  let interval;

  const carouselTrack = document.getElementById('carouselTrack');
  const slides = carouselTrack.querySelectorAll('.carousel-slide');

  const firstSlideClone = slides[0].cloneNode(true);
  carouselTrack.appendChild(firstSlideClone);

  carouselTrack.style.transform = 'translateX(0)';
  carouselTrack.style.transition = 'transform 1.2s ease-in-out'; // Always slow and smooth

  function updateCarousel() {
    carouselTrack.style.transform = `translateX(-${currentIndex * 100}%)`;
  }

  function nextSlide() {
    currentIndex++;
    updateCarousel();

    // If moved to the cloned slide
    if (currentIndex === slides.length) {
      setTimeout(() => {
        // Instantly jump to the real first slide (no animation)
        carouselTrack.style.transition = 'none';
        currentIndex = 0;
        updateCarousel();

        // Re-enable smooth transition for future slides
        requestAnimationFrame(() => {
          requestAnimationFrame(() => {
            carouselTrack.style.transition = 'transform 1.2s ease-in-out';
          });
        });
      }, 1200); // Wait for full 1.2s transition to finish
    }
  }

  function startAutoScroll() {
    interval = setInterval(nextSlide, autoScrollSpeed);
  }

  function stopAutoScroll() {
    clearInterval(interval);
  }

  startAutoScroll();

  carouselTrack.addEventListener('mouseenter', stopAutoScroll);
  carouselTrack.addEventListener('touchstart', stopAutoScroll);

  carouselTrack.addEventListener('mouseleave', startAutoScroll);
  carouselTrack.addEventListener('touchend', startAutoScroll);
});
