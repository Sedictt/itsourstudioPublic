document.addEventListener("DOMContentLoaded", () => {
  const loadingScreen = document.getElementById("loading-screen");

  // Fade in on load
  requestAnimationFrame(() => {
    document.body.classList.add("page-loaded");
  });

  document.querySelectorAll("a").forEach(link => {
    const href = link.getAttribute("href");

    if (
      href &&
      !href.startsWith("http") &&
      !href.startsWith("#") &&
      !link.hasAttribute("target")
    ) {
      link.addEventListener("click", function (e) {
        e.preventDefault();

        // Show the loading screen
        document.body.classList.add("show-loading");
        document.body.classList.remove("page-loaded");

        // Delay navigation so the screen is visible for a while
        setTimeout(() => {
          window.location.href = href;
        }, 1000); // <- delay navigation for 1 second
      });
    }
  });
});

