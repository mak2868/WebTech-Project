// Author: Felix

document.addEventListener('DOMContentLoaded', () => {
  const navbar        = document.getElementById('navbar');
  const darkmodeBtn   = document.getElementById('darkmodeBtn');
  const logoImg       = document.getElementById('navbarLogo');
  const darkmodeIcon  = document.getElementById('darkmodeIcon');
  const cartIcon      = document.getElementById('cart-icon');
  const body          = document.body;

  let isDark     = localStorage.getItem('darkMode') === 'true';
  let isScrolled = false;
  let isHover    = false;

  function updateNavbar() {
    navbar.classList.toggle('dark-mode', isDark);
    navbar.classList.toggle('scrolled', isScrolled);
    navbar.classList.toggle('hover', isHover);
    body.classList.toggle('dark-mode', isDark);

    if (darkmodeIcon) {
      darkmodeIcon.src = isDark
        ? 'images/sonne.png'
        : 'images/mond.png';
    }
  }

  // Global verfügbare Funktion für Icon-Wechsel
  window.updateCartIcon = function updateCartIcon() {
    if (!cartIcon) return;

    const BASE_URL = '/WebTech-Project/public';
    const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';

    if (isLoggedIn) {
      fetch('index.php?page=get-cart')
        .then(res => res.json())
        .then(cart => {
          cartIcon.src = cart.length > 0
            ? BASE_URL + '/images/einkaufswagen-full.png'
            : BASE_URL + '/images/einkaufswagen.png';
        })
        .catch(() => {
          cartIcon.src = BASE_URL + '/images/einkaufswagen.png';
        });
    } else {
      const cart = JSON.parse(localStorage.getItem('cart') || '[]');
      cartIcon.src = cart.length > 0
        ? BASE_URL + '/images/einkaufswagen-full.png'
        : BASE_URL + '/images/einkaufswagen.png';
    }
  };

  // Darkmode Toggle
  darkmodeBtn.addEventListener('click', () => {
    isDark = !isDark;
    localStorage.setItem('darkMode', isDark);
    updateNavbar();
  });

  // Hover-Effekt
  navbar.addEventListener('mouseenter', () => {
    isHover = true;
    updateNavbar();
  });

  navbar.addEventListener('mouseleave', () => {
    isHover = false;
    updateNavbar();
  });

  // Scroll-Effekt
  window.addEventListener('scroll', () => {
    isScrolled = window.scrollY > 0;
    updateNavbar();
  });

  updateNavbar();
  updateCartIcon();
});
