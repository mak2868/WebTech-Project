document.addEventListener("DOMContentLoaded", function () {
  const navbar = document.querySelector(".navbar");
  const body = document.body;
  const darkmodeBtn = document.getElementById("darkmodeBtn");

  // === SCROLL-EVENT: transparent → weiß beim echten Scrollen ===
  const updateNavbarScrollState = () => {
    if (window.scrollY > 0) {
      navbar.classList.add("scrolled");
    } else {
      navbar.classList.remove("scrolled");
    }
  };
  window.addEventListener("scroll", updateNavbarScrollState);
  updateNavbarScrollState(); // Initial prüfen

  // === HOVER-EVENT: weiß beim Überfahren ===
  navbar.addEventListener("mouseenter", () => navbar.classList.add("hover"));
  navbar.addEventListener("mouseleave", () => navbar.classList.remove("hover"));

  // === DARKMODE-TOGGLE: transparent weiß/dunkel-Modus umschalten ===
  if (darkmodeBtn) {
    darkmodeBtn.addEventListener("click", () => {
      body.classList.toggle("darkmode");
    });
  }
});
